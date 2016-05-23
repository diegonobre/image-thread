<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @Route("/export")
 */
class ExportController extends Controller
{
    /**
     * @Route("/csv", name="export_csv")
     * @return StreamedResponse
     */
    public function csvAction()
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

        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="export.csv"');

        return $response;
    }
}
