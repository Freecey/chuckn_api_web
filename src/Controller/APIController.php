<?php

namespace App\Controller;

use App\Entity\Jokes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class APIController extends AbstractController
{
    #[Route('/api/rand', name: 'api', methods: ["GET"])]
    public function rand(): Response
    {
        $jokesRand = $this->getDoctrine()->getRepository(Jokes::class)->findOneRandom();

        $response = $this->json($jokesRand, 200, [], ['groups' => 'jokes:read']);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
//        return $this->render('api/index.html.twig', [
//            'controller_name' => 'APIController',
//        ]);
    }

    #[Route('/api/{id}', name: 'api.jokeID', methods: ["GET"])]
    public function getOne(int $id): Response
    {
        $jokesRand = $this->getDoctrine()->getRepository(Jokes::class)->findOneBy(['id' => $id]);

        $response = $this->json($jokesRand, 200, [], ['groups' => 'jokes:read']);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
//        return $this->render('api/index.html.twig', [
//            'controller_name' => 'APIController',
//        ]);
    }
}
