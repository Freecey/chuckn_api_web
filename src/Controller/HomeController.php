<?php

namespace App\Controller;

use App\Repository\JokesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(JokesRepository $jokesRepository): Response
    {
        $jokesRand = $jokesRepository->findOneRandom();

        $jokes = $jokesRepository->findBy(
            array(),
            array('id' => 'DESC'),
            5,
            0
        );

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'jokes' => $jokes,
            'jokeRand' => $jokesRand,
        ]);
    }
}
