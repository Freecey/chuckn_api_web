<?php

namespace App\Controller;

use App\Entity\Jokes;
use App\Repository\JokesRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class APIController extends AbstractController
{
    /**
     * @throws \Exception
     */
    #[Route('/api/', name: 'api.index')]
    public  function index(JokesRepository $jokesRepository, SerializerInterface $serializer): Response
    {
        $randJson = $jokesRepository->findOneRandom();
        $randJson = $serializer->serialize($randJson, 'json', ['groups' => 'jokes:read']);

        $oneNumber = random_int(1, 9999);
        $oneJson = $jokesRepository->findOneBy(['id' => $oneNumber]);
        $oneJson = $serializer->serialize($oneJson, 'json', ['groups' => 'jokes:read']);

        return $this->render('api/index.html.twig', [
            'controller_name' => 'APIController',
            'rand_json' => $randJson,
            'one_json' => $oneJson,
            'one_numb' => $oneNumber,
        ]);
    }

    #[Route('/api/rand', name: 'api.rand', methods: ["GET"])]
    public function rand(JokesRepository $jokesRepository): Response
    {
        $jokesRand = $jokesRepository->findOneRandom();

        $response = $this->json($jokesRand, 200, [], ['groups' => 'jokes:read']);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    #[Route('/api/joke/{id}', name: 'api.jokeID', methods: ["GET"])]
    public function getOne(JokesRepository $jokesRepository, int $id): Response
    {
        $jokesRand = $jokesRepository->findOneBy(['id' => $id]);

        $response = $this->json($jokesRand, 200, [], ['groups' => 'jokes:read']);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
