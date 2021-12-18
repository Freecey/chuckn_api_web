<?php

namespace App\Controller;

use App\Entity\Jokes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class APIController extends AbstractController
{
    #[Route('/api/', name: 'api.index')]
    public  function index(): Response
    {
        $randjson = $this->rand()->getContent();

        $oneNumber = random_int(1, 9999);
        $oneJson = $this->getOne($oneNumber)->getContent();

        return $this->render('api/index.html.twig', [
            'controller_name' => 'APIController',
            'rand_json' => $randjson,
            'one_json' => $oneJson,
            'one_numb' => $oneNumber,
        ]);
    }

    #[Route('/api/rand', name: 'api.rand', methods: ["GET"])]
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

    #[Route('/api/joke/{id}', name: 'api.jokeID', methods: ["GET"])]
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
