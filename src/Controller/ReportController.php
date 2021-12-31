<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    #[Route('/report', name: 'report')]
    public function index(): Response
    {
        return $this->render('report/index.html.twig', [
            'controller_name' => 'ReportController',
        ]);
    }

    #[Route('/report/{id}', name: 'report.joke')]
    public function joke(int $id): Response
    {

        $content = "HELLO ".$id;
        $textResponse = new Response($content , 200);
        $textResponse->headers->set('Content-Type', 'text/plain');

        return $textResponse;
    }
}
