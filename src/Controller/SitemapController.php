<?php

namespace App\Controller;

use App\Entity\Jokes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitemapController extends AbstractController
{
    #[Route('/sitemap.xml', name: 'sitemap', defaults: ['_format' => 'xml'])]
    public function index(Request $request): Response
    {
        $hostname =  $request->getSchemeAndHttpHost();

        $urls = [];

        $urls[] = ['loc' => $this->generateUrl('home')];
        $urls[] = ['loc' => $this->generateUrl('api.index')];
        $urls[] = ['loc' => $this->generateUrl('jokes.index')];
        $urls[] = ['loc' => $this->generateUrl('joke.rand')];

        foreach ($this->getDoctrine()->getRepository(Jokes::class)->findAll() as $joke)
        {
            $urls[] = [
                'loc' => $this->generateUrl('joke.show', ['id' => $joke->getId()]),
                'lastmod' => ($joke->getUpdatedAt() != null) ? $joke->getUpdatedAt()->format('Y-m-d') : $joke->getCreatedAt()->format('Y-m-d')
            ];
        }

        $response = new Response(
            $this->renderView('sitemap/index.xml.twig', [
                'urls' => $urls,
                'hostname' => $hostname,
            ]),
            200
        );

        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }
}
