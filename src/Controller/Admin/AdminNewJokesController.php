<?php

namespace App\Controller\Admin;

use App\Entity\Jokes;
use App\Form\JokeFormType;
use App\Form\JokesAdminType;
use App\Form\NewJokesAdminType;
use App\Repository\JokesRepository;
use App\Repository\NewJokesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminNewJokesController extends AbstractController
{
    #[Route('/admin/newjokes', name: 'admin.newjokes.index')]
    public function index(Request $request, PaginatorInterface $paginator, NewJokesRepository $newJokesRepository): Response
    {
        $startTime = microtime(true);

//        $SearchTerms = $request->query->get('search');
////        $SearchTerms = $request->request->get('search');
//        $SearchReq = $SearchTerms;
//        $LongArray = [];
//
//        for ($i = 0; $this->get_string_between($SearchReq, '"', '"') != ""; $i++) {
//            $LongSearch = $this->get_string_between($SearchReq, '"', '"');
//            array_push($LongArray, $LongSearch);
//            $SearchReq = str_replace($LongSearch, "", $SearchReq);
//            $SearchReq = str_replace('""', "", $SearchReq);
//        }
//
//        $ArraySearch = explode(" ", $SearchReq);
//        $ArraySearch = array_merge($LongArray, $ArraySearch);
//        $ArraySearch = array_values(array_filter($ArraySearch));

        $allNewJoke = $newJokesRepository->findAll();

        $jokes = $paginator->paginate(
            $allNewJoke, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            25 // Nombre de résultats par page
        );

        return $this->render('admin/newjokes/index.html.twig', [
            'controller_name' => 'AdminJokesController',
            'jokes' => $jokes,
            'SearchTerms' => '',
            'execTime' => microtime(true) - $startTime,
        ]);
    }
    #[Route('admin/newjokes/{id}', name: 'admin.newjokes.show')]
    public function show(int $id, Request $request, EntityManagerInterface $manager, NewJokesRepository $newJokesRepository): Response
    {
        if ($id == null) {
            $joke = new Jokes();
        } else {
            $joke = $newJokesRepository->findOneBy(['id' => $id]);
        }

        $form = $this->createForm(NewJokesAdminType::class, $joke);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($joke);
            $manager->flush();
            $this->addFlash('success', 'Jokes Updated, Successfully');

            return $this->redirectToRoute('admin.newjokes.index');
        }

        return $this->render('admin/newjokes/show.html.twig', [
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
