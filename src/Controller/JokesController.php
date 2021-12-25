<?php

namespace App\Controller;

use App\Entity\Jokes;
use App\Entity\JokesRatings;
use App\Form\JokeFormType;
use App\Repository\JokesRepository;
use Doctrine\Persistence\ObjectManager;
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
        $jokesdata = $jokesRepository->findBy(
            array(),
            array('id' => 'DESC')
        );

        $jokes = $paginator->paginate(
            $jokesdata, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
//            5 // Nombre de résultats par page
        );

        return $this->render('jokes/index.html.twig', [
            'controller_name' => 'JokesController',
            'jokes' => $jokes,
        ]);
    }

    #[Route('/jokes/add', name: 'jokes.add')]
    public function add(Request $request): Response
    {
        $joke = new Jokes();

        $form = $this->createForm(JokeFormType::class, $joke);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $joke->setJoke();
            $joke->setValidated(true);

//            $doctrine = $this->getDoctrine()->getManager();
//            $doctrine->persist($joke);
//            $doctrine->flush();
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

    #[Route('/jokes/{id}/rating', name: 'joke.rating')]
    public function rating(): Response
    {
        return $this->json(['code'=>200, 'message'=> 'Futurn Rating'],200);
    }
}

