<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\InvestmentsRepository;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, InvestmentsRepository $invrepo): Response
    {
        $projets= $invrepo->findAll();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'part' => 1,
            'title' => '',
            'titlepage' => '',
            'projets' => $projets,
        ]);
    }
    #[Route('/about', name: 'app_about')]
    public function aboutClient(): Response
    {
        return $this->render('home/about.html.twig', [
            'controller_name' => 'HomeController',
            'part' => 2,
            'title' => 'Qui Sommes-Nous ?',
            'titlepage' => 'À Propos - ',
        ]);
    }
    
}
