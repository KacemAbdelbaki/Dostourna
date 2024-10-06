<?php

namespace App\Controller;

use App\Entity\Investments;
use App\Entity\Category;
use App\Entity\User;
use App\Repository\InvestmentsRepository;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectsController extends AbstractController
{
    #[Route('/projects/{page}', name: 'app_projects')]
    public function index(int $page,Request $request, InvestmentsRepository $invrepo,CategoryRepository $catrepo ): Response
    {
        $itemsPerPage = 4; // Number of items per page
        $totalItems = count($invrepo->findAll()); // Total number of items
        $totalPages = ceil($totalItems / $itemsPerPage); // Calculate the total number of pages
        $projets= $invrepo->findAll();
        $categories= $catrepo->findAll();
        $reccprojets= $invrepo->findAll();
//        $publications = $publicationRepository->findPaginated($page, $itemsPerPage);
//        $totalItems = count($publicationRepository->findAllsortedValide()); // Total number of items

        return $this->render('home/projects.html.twig', [
            'controller_name' => 'ProjectsController',
            'part' => 3,
            'title' => 'Explorez les Projets Innovants',
            'titlepage' => 'Nos Projets',
            'projets' => $projets,
            'totalPages' => $totalPages,
            'curentPage'=>$page,
            'categories'=>$categories,
            'reccprojets' =>$reccprojets,
            //'reccpublications' => $publicationRepository->findAllsortedValide(),
        ]);
    }    
}
