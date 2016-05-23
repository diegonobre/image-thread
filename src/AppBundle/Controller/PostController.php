<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Form\Type\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/post")
 */
class PostController extends Controller
{
    /**
     * @Route("/create", name="post_create")
     * @param Request $request
     */
    public function createAction(Request $request)
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

        return $this->render('default/validation.html.twig', array(
            'errors' => $this->get('validator')->validate($post),
        ));
    }

    /**
     * @Route("/total-number-of-posts", name="post_total_number_of_posts")
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
     * @Route("/total-number-of-views", name="post_total_number_of_views")
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
