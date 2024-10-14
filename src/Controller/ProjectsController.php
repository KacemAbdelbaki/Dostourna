<?php

namespace App\Controller;

use App\Entity\Investments;
use App\Entity\Category;
use App\Entity\User;
use App\Repository\InvestmentsRepository;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use App\Form\InvestmentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ProjectsController extends AbstractController
{
    #[Route('/projects/{cat}/{page}', name: 'app_projects')]
    public function index(int $cat ,int $page,Request $request, InvestmentsRepository $invrepo,CategoryRepository $catrepo ): Response
    {
        $itemsPerPage = 4; // Number of items per page
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
            'stripe_public_key' => $this->getParameter('app.stripe_public_key'),
        ]);
    }    
    #[Route('/details/{id}', name: 'project_detail')]
    public function aboutproject(int $id, InvestmentsRepository $invrepo,CategoryRepository $catrepo): Response
    {
        $categories= $catrepo->findAll();
        $reccprojets= $invrepo->findAllSortedByFundingDifference();
        $projet= $invrepo->find($id);
        return $this->render('home/details.html.twig', [
            'controller_name' => 'HomeController',
            'part' => 3,
            'title' => $projet->getName(),
            'titlepage' => 'Détails - ',
            'projet'=>$projet,
            'categories'=>$categories,
            'reccprojets' =>$reccprojets,
            'stripe_public_key' => $this->getParameter('app.stripe_public_key'),
        ]);
    }
    #[Route('/infos/{id}', name: 'app_project_edit')]
    public function editproject(int $id,Request $request,SluggerInterface $slugger, ParameterBagInterface $params, EntityManagerInterface $entityManager,InvestmentsRepository $invrepo,CategoryRepository $catrepo): Response
    {
        $categories= $catrepo->findAll();
        $reccprojets= $invrepo->findAllSortedByFundingDifference();

        $projet= $invrepo->find($id);
        $form = $this->createForm(InvestmentType::class, $projet);
        $categories= $catrepo->findAll();
        $reccprojets= $invrepo->findAllSortedByFundingDifference();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $projet->setUpdatedAt(new \DateTimeImmutable());
            $imageFile = $form->get('imageFile')->getData(); // Ensure 'imageFile' matches your form field name
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = substr($safeFilename, 0, 10).'-'.uniqid().'.'.$imageFile->guessExtension();
    
                try {
                    $imageFile->move(
                        $params->get('projet_pictures_directory'), // Make sure this parameter is defined in your services.yaml
                        $newFilename
                    );
                    $projet->setImage($newFilename); // Update the entity with the new filename
                } catch (FileException $e) {
                    // Handle exception if something happens during file upload
                }
            }
            $entityManager->flush();

            return $this->redirectToRoute('project_detail', ['id'=> $projet->getId()], Response::HTTP_SEE_OTHER);
        }
        return $this->render('home/updateprojet.html.twig', [
            'controller_name' => 'HomeController',
            'part' => 3,
            'title' => $projet->getName(),
            'titlepage' => 'Projet - ',
            'projet'=>$projet,
            'categories'=>$categories,
            'reccprojets' =>$reccprojets,
            'form' => $form->createView(),
            'stripe_public_key' => $this->getParameter('app.stripe_public_key'),
        ]);
    }
    #[Route('/projet/{id}', name: 'app_projet_delete', methods: ['POST'])]
    public function deleteproject(Request $request,  int $id, EntityManagerInterface $entityManager,InvestmentsRepository $invrepo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$id, $request->request->get('_token'))) {
            $entityManager->remove($invrepo->find($id));
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_projects', ['cat'=> 0,'page'=>1], Response::HTTP_SEE_OTHER);
    }
    #[Route('/projet', name: 'add_project')]
    public function addproject(Request $request,SluggerInterface $slugger, ParameterBagInterface $params, EntityManagerInterface $entityManager,InvestmentsRepository $invrepo,CategoryRepository $catrepo): Response
    {
        $projet = new Investments();
        $form = $this->createForm(InvestmentType::class, $projet);
        $categories= $catrepo->findAll();
        $reccprojets= $invrepo->findAllSortedByFundingDifference();
        $form->handleRequest($request);
        $imageFile = $form->get('imageFile')->getData(); 
        if ($form->isSubmitted() && $form->isValid()) {
            $projet->setUser($this->getUser());
            $projet->setCreatedAt(new \DateTimeImmutable());
            $projet->setUpdatedAt(new \DateTimeImmutable());
            $projet->setStatus("En Cours De Finnacement");
            $projet->
            $imageFile = $form->get('imageFile')->getData(); // Ensure 'imageFile' matches your form field name
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = substr($safeFilename, 0, 10).'-'.uniqid().'.'.$imageFile->guessExtension();
    
                try {
                    $imageFile->move(
                        $params->get('projet_pictures_directory'), // Make sure this parameter is defined in your services.yaml
                        $newFilename
                    );
                    $projet->setImage($newFilename); // Update the entity with the new filename
                } catch (FileException $e) {
                    // Handle exception if something happens during file upload
                }
            }
            $entityManager->persist($projet);
            $entityManager->flush();

            return $this->redirectToRoute('app_projects', ['cat'=> $projet->getCategorie()->getId(),'page'=>1], Response::HTTP_SEE_OTHER);
        }
        return $this->render('home/addproject.html.twig', [
            'controller_name' => 'HomeController',
            'part' => 3,
            'title' => 'nouveau projet',
            'titlepage' => 'Projet - ',
            'categories'=>$categories,
            'reccprojets' =>$reccprojets,
            'projet'=>$projet,
            'form' => $form->createView(),
            'stripe_public_key' => $this->getParameter('app.stripe_public_key'),
        ]);
    }
}
