<?php

namespace App\Controller\Admin;

use App\Entity\Jokes;
use App\Form\JokeFormType;
use App\Repository\JokesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminJokesController extends AbstractController
{
    #[Route('/admin/jokes', name: 'admin.jokes')]
    public function index(Request $request, PaginatorInterface $paginator, JokesRepository $jokesRepository): Response
    {
        $startTime = microtime(true);

        $SearchTerms = $request->query->get('search');
//        $SearchTerms = $request->request->get('search');
        $SearchReq = $SearchTerms;
        $LongArray = [];

        for ($i = 0; $this->get_string_between($SearchReq, '"', '"') != ""; $i++) {
            $LongSearch = $this->get_string_between($SearchReq, '"', '"');
            array_push($LongArray, $LongSearch);
            $SearchReq = str_replace($LongSearch, "", $SearchReq);
            $SearchReq = str_replace('""', "", $SearchReq);
        }

        $ArraySearch = explode(" ", $SearchReq);
        $ArraySearch = array_merge($LongArray, $ArraySearch);
        $ArraySearch = array_values(array_filter($ArraySearch));

        $SearchJoke = $jokesRepository->findSearch($ArraySearch);

        $jokes = $paginator->paginate(
            $SearchJoke, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            25 // Nombre de résultats par page
        );

        return $this->render('admin/jokes/index.html.twig', [
            'controller_name' => 'AdminJokesController',
            'jokes' => $jokes,
            'SearchTerms' => $SearchTerms,
            'execTime' => microtime(true) - $startTime,
        ]);
    }
    #[Route('admin/jokes/{id}', name: 'admin.jokes.show')]
    public function show(int $id, Request $request, EntityManagerInterface $manager, JokesRepository $jokesRepository): Response
    {
        if ($id == null) {
            $joke = new Jokes();
        } else {
            $joke = $jokesRepository->findOneBy(['id' => $id]);
        }

        $form = $this->createForm(JokeFormType::class, $joke);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $joke->setValidated(true);
            $manager->persist($joke);
            $manager->flush();

        }

        return $this->render('admin/jokes/show.html.twig', [
            'controller_name' => 'AdminJokesController',
            'jokeForm' => $form->createView(),
        ]);
    }

    public function get_string_between($string, $start, $end): string
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
}
