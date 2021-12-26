<?php

namespace App\Controller;

use App\Entity\Jokes;
use App\Entity\JokesRatings;
use App\Form\JokeFormType;
use App\Repository\JokesRatingsRepository;
use App\Repository\JokesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class JokesController extends AbstractController
{
    #[Route('jokes', name: 'jokes.index')]
    public function index(Request $request, PaginatorInterface $paginator, JokesRepository $jokesRepository): Response
    {
        $jokesData = $jokesRepository->findBy(
            array(),
            array('ratingScore' => 'DESC', 'id' => 'DESC')
        );

        $jokes = $paginator->paginate(
            $jokesData, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
//            5 // Nombre de résultats par page
        );

        return $this->render('jokes/index.html.twig', [
            'controller_name' => 'JokesController',
            'jokes' => $jokes,
        ]);
    }

    #[Route('/jokes/add', name: 'jokes.add')]
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        $joke = new Jokes();

        $form = $this->createForm(JokeFormType::class, $joke);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $joke->setValidated(true);
            $manager->persist($joke);
            $manager->flush();

//            RESET JOKE FORM
            $joke = new Jokes();
            $form = $this->createForm(JokeFormType::class, $joke);
        }

        return $this->render('jokes/add.html.twig', [
            'controller_name' => 'JokesController',
            'jokeForm' => $form->createView(),
        ]);
    }

    #[Route('/jokes/reset', name: 'jokes.reset')]
    public function reset(Request $request): Response
    {
        $joke = new Jokes();

        $form = $this->createForm(JokeFormType::class, $joke);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $joke->setJoke();
            $joke->setValidated(true);

            $doctrine = $this->getDoctrine()->getManager();
            $doctrine->persist($joke);
            $doctrine->flush();
        }

//        $file = file('https://testdev2.iweep.be/nodup-ok.txt');
//        shuffle($file);
//
//        foreach ($file as $line )
//            {
//                $joke = new Jokes();
//                $joke->setJoke(trim(preg_replace('/\s\s+/', ' ', $line)));
//                $joke->setValidated(true);
//                $doctrine = $this->getDoctrine()->getManager();
//                $doctrine->persist($joke);
//                $doctrine->flush();
//            }

        return $this->render('jokes/add.html.twig', [
            'controller_name' => 'JokesController',
            'jokeForm' => $form->createView(),
        ]);
    }

    #[Route('/jokes/rand', name: 'joke.rand')]
    public function rand(JokesRepository $jokesRepository): Response
    {
        $jokesRand = $jokesRepository->findOneRandom();
        return $this->render('jokes/rand.html.twig', [
            'controller_name' => 'JokesController.Rand',
            'joke' => $jokesRand
        ]);
    }

    #[Route('/jokes/{id}', name: 'joke.show')]
    public function show(Request $request, int $id, JokesRepository $jokesRepository): Response
    {
        $joke = $jokesRepository->findOneBy(['id' => $id]);

        if ($joke == null)
        {
            return $this->redirectToRoute('jokes.index');
        }

        return $this->render('jokes/show.html.twig', [
            'controller_name' => 'JokesController',
            'joke' => $joke,
        ]);
    }

    #[Route('/jokes/{id}/rating/{rate}', name: 'joke.rating', methods: ["POST"])]
    public function rating(EntityManagerInterface $manager, int $id, int $rate, JokesRepository $jokesRepository, JokesRatingsRepository $jokesRatingsRepository): Response
    {
        if (  $rate < 1 || 5 < $rate ){
            return $this->json(['code'=>200, 'message'=> "rating must be between 1-5"],200);
        }

        $onJoke = $jokesRepository->findOneBy(['id'=> $id]);

        if ( $onJoke == null ){
            return $this->json(['code'=>200, 'message'=> "Rating not possible, this joke id don't exist"],200);
        }

        $search = $jokesRatingsRepository->findOneLess24($onJoke);

        if ( $search != null ){
            return $this->json(['code'=>200, 'message'=> "Maximum un vote par jour"],200);
        }

        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';

        $jokeRating = new JokesRatings();
        $jokeRating->setRating($rate)
            ->addJoke($onJoke)
            ->setIp($ipaddress);
        $manager->persist($jokeRating);
        $manager->flush();

        $onJoke = $jokesRepository->findOneBy(['id'=> $id]);

        $totalRateScore = 0;
        foreach ($onJoke->getJokesRatings() as $rateScore) {
            $totalRateScore += $rateScore->getRating();
        }
        $numberOfRating = count($onJoke->getJokesRatings()) != 0 ? count($onJoke->getJokesRatings()) : 1;
        $RatingScore = $totalRateScore/$numberOfRating;

        $onJoke->setRatingScore($RatingScore);
        $manager->persist($onJoke);
        $manager->flush();

        return $this->json(['code'=>200, 'status' => 'add', 'message'=> 'Votre vote a été ajouté ', 'jokeId' => $onJoke->getId(), 'nmbOfRate' => count($onJoke->getJokesRatings()), 'ratingScore' => $RatingScore],200);
    }
}

