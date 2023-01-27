<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Controller\admin;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminCategoriesController
 *
 * @author pilou
 */
class AdminCategoriesController extends AbstractController {
    
        /**
     * 
     * @var FormationRepository
     */
    private $formationRepository;
    
    /**
     * 
     * @var CategorieRepository
     */
    private $categorieRepository;
    
    function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository, PlaylistRepository $playlistRepository) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository= $categorieRepository;
    }
    
    /**
     * @Route("/admin/categories", name="admin.categories")
     * @return Response
     */
    public function index(): Response{
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/admin.categories.html.twig", [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }
    
    /**
     * @Route("/admin/categories/formation/{id}", name="admin.categories.showone")
     * @param type $id
     * @return Response
     */
    public function showOne($id): Response{
        $formation = $this->formationRepository->find($id);
        return $this->render("admin/admin.formation.html.twig", [
            'formation' => $formation
        ]);        
    }   
    
    /**
     * @Route("/admin/categories/suppr/{id}", name="admin.categoriessuppr")
     * @param Categorie $categorie
     * @return Response
     */
    public function suppr(Categorie $categorie): Response{
        $this->categorieRepository->remove($categorie, true);
        return $this->redirectToRoute('admin.categories');
    }
    
    /**
     * @Route("/admin/categories/edit/{id}", name="admin.categories.edit")
     * @param Categorie $categorie
     * @param Request &request
     * @return Response
     */
    public function edit(Categorie $categorie, Request $request): Response{
        $formCategorie = $this->createForm(CategorieType::class, $categorie);
        
        $formCategorie->handleRequest($request);
        if ($formCategorie->isSubmitted() && $formCategorie->isValid()){
            $this->categorieRepository->add($categorie, true);
            return $this->redirectToRoute('admin.categories');
        }
        
        return $this->render("admin/admin.categorie.edit.html.twig", [
            'categorie' => $categorie,
            'formcategorie' => $formCategorie->createView()
        ]);
    }
    
    /**
     * @Route("/admin/categories/ajout", name="admin.categorie.ajout")
     * @param Request &request
     * @return Response
     */
    public function ajout(Request $request): Response{
        $categorie = new Categorie();
        
        $formCategorie = $this->createForm(CategorieType::class, $categorie);
        
        $formCategorie->handleRequest($request);
        if ($formCategorie->isSubmitted() && $formCategorie->isValid()){
            $this->categorieRepository->add($categorie, true);
            return $this->redirectToRoute('admin.categories');
        }
        
        return $this->render("admin/admin.categorie.ajout.html.twig", [
            'categorie' => $categorie,
            'formcategorie' => $formCategorie->createView()
        ]);
    }
    
}
