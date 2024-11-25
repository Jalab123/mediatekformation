<?php

namespace App\Controller\admin;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur admin des formations.
 *
 * @author pilou
 */
class AdminFormationsController extends AbstractController {
    
    /**
     * Repository des formations.
     * @var FormationRepository
     */
    private $formationRepository;
    
    /**
     * Repository des catégories.
     * @var CategorieRepository
     */
    private $categorieRepository;
    
    /**
     * Lien vers la page des formations.
     */
    const LIEN_FORMATIONS = "admin/admin.formations.html.twig";
    
    /**
     * Lien vers la route des formations.
     */
    const ROUTE_FORMATIONS = "admin.formations";
    
    /**
     * Constructeur.
     * @param FormationRepository $repository
     */
    public function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }
    
    /**
     * Fonction exécutée lors du chargement de la page.
     * @Route("/admin/formations", name="admin.formations")
     * @return Response
     */
    #[Route('/admin/formations/', name: self::ROUTE_FORMATIONS)]
    public function index(): Response{
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::LIEN_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    /**
     * Fonction permettant de trier sur un certain champ, un certain ordre, et une certaine table.
     * @Route("/admin/formations/tri/{champ}/{ordre}/{table}", name="admin.formations.sort")
     * @param type $champ
     * @param type $ordre
     * @param type $table
     * @return Response
     */
    #[Route('/admin/formations/tri/{champ}/{ordre}/{table}', name: 'admin.formations.sort')]
    public function sort($champ, $ordre, $table=""): Response{
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::LIEN_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }     

    /**
     * Fonction permettant de filtrer sur un certain champ et une certaine table.
     * @Route("/admin/formations/recherche/{champ}/{table}", name="admin.formations.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    #[Route('/admin/formations/recherche/{champ}/{table}', name: 'admin.formations.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::LIEN_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }  
    
    /**
     * Fonction permettant de supprimer une formation.
     * @Route("/admin/formations/suppr/{id}", name="admin.formation.suppr")
     * @param int $id
     * @return Response
     */
    #[Route('/admin/formations/suppr/{id}', name: 'admin.formation.suppr')]
    public function suppr(int $id): Response {
        $formation = $this->formationRepository->find($id);
        $this->formationRepository->remove($formation);
        return $this->redirectToRoute(self::ROUTE_FORMATIONS);
    }
    
    /**
     * Fonction permettant de modifier une formation.
     * @Route("/admin/formations/edit/{id}", name="admin.formation.edit")
     * @param int $id
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/formations/edit/{id}', name: 'admin.formation.edit')]
    public function edit(int $id, Request $request): Response{
       $formation = $this->formationRepository->find($id);
       $formFormation = $this->createForm(FormationType::class, $formation);
       
       $formFormation->handleRequest($request);
       if($formFormation->isSubmitted() && $formFormation->isValid()){
           $this->formationRepository->add($formation);
           return $this->redirectToRoute(self::ROUTE_FORMATIONS);
       }
       
       return $this->render("admin/admin.formation.edit.html.twig", [
           'formation' => $formation,
           'formformation' => $formFormation->createView()
       ]);
    }
    
    /**
     * Fonction permettant d'ajouter une formation.
     * @Route("/admin/formations/ajout", name="admin.formation.ajout")
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/formations/ajout', name: 'admin.formation.ajout')]
    public function add(Request $request): Response{
       $formation = new Formation();
       $formFormation = $this->createForm(FormationType::class, $formation);
        
       $formFormation->handleRequest($request);
       if($formFormation->isSubmitted() && $formFormation->isValid()){
           $this->formationRepository->add($formation);
           return $this->redirectToRoute(self::ROUTE_FORMATIONS);
       }
       
       return $this->render("admin/admin.formation.ajout.html.twig", [
           'formation' => $formation,
           'formformation' => $formFormation->createView()
       ]);
    }
}
