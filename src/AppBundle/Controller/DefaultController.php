<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Form\Type\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction($validations = null)
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
            'validations' => $validations,
        ));
    }
}
