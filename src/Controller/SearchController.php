<?php

namespace App\Controller;

use App\Entity\Jokes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'search')]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $SearchTerms = $request->query->get('search');
//        $SearchTerms = $request->request->get('search');
        $SearchReq = $SearchTerms;
        $LongArray = [];

        for ($i = 0 ; $this->get_string_between($SearchReq, '"', '"') != "" ; $i++)
        {
            $LongSearch = $this->get_string_between($SearchReq, '"', '"');
            array_push($LongArray, $LongSearch);
            $SearchReq = str_replace($LongSearch, "",$SearchReq );
            $SearchReq = str_replace('""', "",$SearchReq );
        }

        $ArraySearch = explode(" ", $SearchReq);
        $ArraySearch = array_merge($LongArray, $ArraySearch);
        $ArraySearch = array_values(array_filter($ArraySearch));

        $SearchJoke = $this->getDoctrine()->getRepository(Jokes::class)->findSearch($ArraySearch);

        $jokes = $paginator->paginate(
            $SearchJoke, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
//            5 // Nombre de résultats par page
        );

        return $this->render('search/index.html.twig', [
            'controller_name' => 'SearchController',
            'jokes' => $jokes,
            'SearchTerms' => $SearchTerms,
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
