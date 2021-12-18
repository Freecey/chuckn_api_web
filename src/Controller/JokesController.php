<?php

namespace App\Controller;

use App\Entity\Jokes;
use App\Form\JokeFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class JokesController extends AbstractController
{
    /**
     * @Route("/jokes", name="jokes.index")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $jokesdata = $this->getDoctrine()->getRepository(Jokes::class)->findBy(
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

//            $doctrine = $this->getDoctrine()->getManager();
//            $doctrine->persist($joke);
//            $doctrine->flush();
        }

        return $this->render('jokes/add.html.twig', [
            'controller_name' => 'JokesController',
            'jokeForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/jokes/reset", name="jokes.reset")
     */
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
}

