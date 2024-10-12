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
    #[Route('/projects/{cat}/{page}', name: 'app_projects')]
    public function index(int $cat ,int $page,Request $request, InvestmentsRepository $invrepo,CategoryRepository $catrepo ): Response
    {
        $itemsPerPage = 2; // Number of items per page
        $Cat= $catrepo->find($cat);
       if ( $Cat == null ) { $totalItems = count($invrepo->findAllSortedByFundingDifference()); // Total number of items
        $totalPages = ceil($totalItems / $itemsPerPage); // Calculate the total number of pages
        $projets= $invrepo->findPaginated($page, $itemsPerPage);}
        else 
        { $totalItems = count($invrepo->findByCategory($cat)); // Total number of items
            $totalPages = ceil($totalItems / $itemsPerPage); // Calculate the total number of pages
            $projets= $invrepo->findPaginatedbycat($page, $itemsPerPage,$cat);}
        $categories= $catrepo->findAll();
        $reccprojets= $invrepo->findAllSortedByFundingDifference();
       
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
            'cat'=>$Cat,
        ]);
    }    
    #[Route('/details/{id}', name: 'project_detail')]
    public function aboutproject(int $id): Response
    {
        return $this->render('home/details.html.twig', [
            'controller_name' => 'HomeController',
            'part' => 3,
            'title' => 'project categorie',
            'titlepage' => 'Détails - ',
        ]);
    }
    #[Route('/projet', name: 'add_project')]
    public function addproject(): Response
    {
        return $this->render('home/addproject.html.twig', [
            'controller_name' => 'HomeController',
            'part' => 3,
            'title' => 'new project',
            'titlepage' => 'Projet - ',
        ]);
    }
}
