<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Form\Type\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PostController extends Controller
{
    /**
     * @Route("/post/save", name="post_save")
     * @param Request $request
     */
    public function postAction(Request $request)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        $validator = $this->get('validator');
        $errors = $validator->validate($post);

        if (count($errors) > 0) {
            /*
             * Uses a __toString method on the $errors variable which is a
             * ConstraintViolationList object. This gives us a nice string
             * for debugging.
             */
            $errorsString = (string) $errors;

            return $this->render('default/validation.html.twig', array(
                'errors' => $errors,
            ));
        }

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
     * @Route("/post/export", name="post_export")
     * @return StreamedResponse
     */
    public function exportAction()
    {
        $response = new StreamedResponse();
        $response->setCallback(function() {
            $handle = fopen('php://output', 'w+');

            // Add the header of the CSV file
            fputcsv($handle, array('title', 'name'),';');

            // Query data from database
            $posts = $this->getDoctrine()
                ->getRepository('AppBundle:Post')
                ->getExportPosts();

            // Add the data queried from database
            foreach ($posts as $post) {
                fputcsv(
                    $handle, // The file pointer
                    array($post['title'], $post['name']), // The fields
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

    /**
     * @Route("/post/total-number-of-posts", name="post_total_number_of_posts")
     * @return Response
     */
    public function totalNumberOfPostsAction()
    {
        $totalNumberOfPosts = $this->getDoctrine()
            ->getRepository('AppBundle:Post')
            ->getTotalNumberOfPosts();

        $response = new Response(json_encode(array('totalNumberOfPosts' => $totalNumberOfPosts)));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/post/total-number-of-views", name="post_total_number_of_views")
     * @return Response
     */
    public function totalNumberOfViewsAction()
    {
        $totalNumberOfViews = $this->getDoctrine()
            ->getRepository('AppBundle:Analytics')
            ->getTotalNumberOfViews();

        $response = new Response(json_encode(array('totalNumberOfViews' => $totalNumberOfViews)));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
