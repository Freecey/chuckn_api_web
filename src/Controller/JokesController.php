<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JokesController extends AbstractController
{
    /**
     * @Route("/jokes", name="jokes")
     */
    public function index(): Response
    {
        return $this->render('jokes/index.html.twig', [
            'controller_name' => 'JokesController',
        ]);
    }
}
