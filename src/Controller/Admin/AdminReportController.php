<?php

namespace App\Controller\Admin;

use App\Entity\Jokes;
use App\Form\JokeFormType;
use App\Form\JokesAdminType;
use App\Repository\JokesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminReportController extends AbstractController
{
    #[Route('/admin/report', name: 'admin.report.index')]
    public function index(Request $request, PaginatorInterface $paginator, JokesRepository $jokesRepository): Response
    {
        $SearchJoke = $jokesRepository->findReports();

        $jokes = $paginator->paginate(
            $SearchJoke, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            25 // Nombre de résultats par page
        );

        return $this->render('admin/reports/index.html.twig', [
            'controller_name' => 'AdminReportsController',
            'jokes' => $jokes,
        ]);
    }
    #[Route('admin/report/{id}', name: 'admin.report.show')]
    public function show(int $id, Request $request, EntityManagerInterface $manager, JokesRepository $jokesRepository): Response
    {
        $joke = $jokesRepository->findOneBy(['id' => $id]);


//        $form = $this->createForm(JokesAdminType::class, $joke);
//
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid())
//        {
//            $joke->setValidated(true);
//            $manager->persist($joke);
//            $manager->flush();
//            $this->addFlash('success', 'Jokes Updated, Successfully');
//
//            return $this->redirectToRoute('admin.jokes');
//        }

        return $this->render('admin/reports/show.html.twig', [
            'controller_name' => 'AdminReportsController',
            'joke' => $joke,
        ]);
    }
}
