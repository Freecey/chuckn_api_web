<?php

namespace App\Controller;

use App\Entity\Jokes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $jokesRand = $this->getDoctrine()->getRepository(Jokes::class)->findOneRandom();

//        var_dump($jokesRand);
//        die;
        $jokes = $this->getDoctrine()->getRepository(Jokes::class)->findBy(
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
