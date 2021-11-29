<?php

namespace App\Controller;

use App\Entity\Jokes;
use App\Form\JokeFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JokesController extends AbstractController
{
    /**
     * @Route("/jokes", name="jokes.index")
     */
    public function index(): Response
    {
        $jokes = $this->getDoctrine()->getRepository(Jokes::class)->findAll();

        return $this->render('jokes/index.html.twig', [
            'controller_name' => 'JokesController',
            'jokes' => $jokes,
        ]);
    }

    /**
     * @Route("/jokes/add", name="jokes.add")
     */
    public function add(Request $request): Response
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
//                $joke->setJoke($line);
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
}
