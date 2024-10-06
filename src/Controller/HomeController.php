<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'part' => 1,
            'title' => '',
            'titlepage' => '',
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
