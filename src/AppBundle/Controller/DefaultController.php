<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Form\Type\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        // total number of posts
        $totalNumberOfPosts = $this->getDoctrine()
            ->getRepository('AppBundle:Post')
            ->getTotalNumberOfPosts();

        $totalNumberOfViews = 0;
        if ($totalNumberOfPosts > 0) {
            $totalNumberOfViews = $this->getDoctrine()
                ->getRepository('AppBundle:Analytics')
                ->getUpdatedTotalNumberOfViews();
        }

        // list images
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('AppBundle:Post')->findBy(array(), array('date' => 'desc'));

        return $this->render('default/index.html.twig', array(
            'form' => $form->createView(),
            'posts' => $posts,
            'totalNumberOfPosts' => $totalNumberOfPosts,
            'totalNumberOfViews' => $totalNumberOfViews,
        ));
    }

    /**
     * @Route("/post", name="post")
     * @param Request $request
     */
    public function postAction(Request $request)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isValid()) {
            // $file stores the uploaded PDF file
            $file = $post->getImgName();

            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            // Move the file to the directory where brochures are stored
            $imagesDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/images';
            $file->move($imagesDir, $fileName);

            // Update the 'brochure' property to store the PDF file name
            // instead of its contents
            $post->setImgName($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirect($this->generateUrl('homepage'));
        }
    }

    /**
     * @Route("/export", name="export")
     * @return StreamedResponse
     */
    public function exportAction()
    {
        $response = new StreamedResponse();
        $response->setCallback(function() {
            $handle = fopen('php://output', 'w+');

            // Add the header of the CSV file
            fputcsv($handle, array('id', 'title'),';');
            // Query data from database

            $results = $this->getDoctrine()
                ->getRepository('AppBundle:Post')
                ->getExportPosts();

            // Add the data queried from database

            foreach ($results as $result) {
                fputcsv(
                    $handle, // The file pointer
                    array($result['id'], $result['title']), // The fields
                    ';' // The delimiter
                );
            }

            fclose($handle);
        });

        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="export.csv"');

        return $response;
    }
}
