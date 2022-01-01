<?php

namespace App\Controller;

use App\Entity\Report;
use App\Form\ReportType;
use App\Repository\JokesRepository;
use App\Repository\ReportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    #[Route('/report', name: 'report')]
    public function index(): Response
    {
        return $this->render('report/index.html.twig', [
            'controller_name' => 'ReportController',
        ]);
    }

    #[Route('/report/{id}', name: 'report.formReport', methods: ['GET'])]
    public function reportId(Request $request, int $id, JokesRepository $jokesRepository, EntityManagerInterface $manager, ReportRepository $reportRepository): Response
    {

        $ipaddress = $this->getIPAddress();

        $onJoke = $jokesRepository->findOneBy(['id'=> $id]);

        $search = $reportRepository->findOneLess7day($onJoke, $ipaddress);

        if ( $search != null ){
            return $this->render('report/already.html.twig', [
                'controller_name' => 'ReportController',
            ]);
        }

        $report = new Report();

        $form = $this->createForm(ReportType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()
            && $form->isValid()
        )
        {
            $report = $form->getData();
            $report->addJoke($onJoke);
            $report->setIp($ipaddress);
            $manager->persist($report);
            $manager->flush();

            return $this->render('report/thank.html.twig',[
                'controller_name' => 'ReportController',
            ]);
        }

        return $this->render('report/joke.html.twig', [
            'controller_name' => 'ReportController',
            'ReportForm' => $form->createView(),
        ]);
    }

    #[Route('/report/post/{id}', name: 'report.postReport', methods: ['POST'])]
    public function postReport(Request $request, int $id, JokesRepository $jokesRepository, ReportRepository $reportRepository, EntityManagerInterface $manager): Response
    {
        $ipaddress = $this->getIPAddress();

        $onJoke = $jokesRepository->findOneBy(['id'=> $id]);

        $search = $reportRepository->findOneLess7day($onJoke, $ipaddress);

        if ( $search != null ){
            return $this->render('report/already.html.twig', [
                'controller_name' => 'ReportController',
            ]);
        }

        $submittedToken = $request->request->get('token');
        if ($this->isCsrfTokenValid('report-t-'.$id, $submittedToken)) {

            $report = new Report();

            $report->setReason($request->get('reason'));
            $report->addJoke($onJoke);
            $report->setIp($ipaddress);
            $manager->persist($report);
            $manager->flush();

            return $this->render('report/thank.html.twig',[
                'controller_name' => 'ReportController',
            ]);

        }
//
        return $this->render('report/error.html.twig', [
            'controller_name' => 'ReportController',
        ]);
    }

    /**
     * @return string
     */
    public function getIPAddress() : string
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        }
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else if(isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        }
        else if(isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        }
        else if(isset($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        }
        else if(isset($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        }
        else {
            $ipaddress = 'UNKNOWN';
        }
        return $ipaddress;
    }
}
