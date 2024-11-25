<?php

namespace App\Controller\admin;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur admin des catégories.
 *
 * @author pilou
 */
class AdminCategoriesController extends AbstractController {
    
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
     * Lien vers la page des catégories.
     */
    const LIEN_CATEGORIES = "admin/admin.categories.html.twig";
    
    /**
     * Lien vers la route des catégories.
     */
    const ROUTE_CATEGORIES = "admin.categories";
    
    /**
     * Constructeur.
     * @param CategorieRepository $categorieRepository
     * @param FormationRepository $formationRespository
     */
    function __construct( 
            CategorieRepository $categorieRepository,
            FormationRepository $formationRespository) {
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRespository;
    }
    
    /**
     * Fonction exécutée lors du chargement de la page.
     * @Route("/admin/categories", name="admin.categories")
     * @return Response
     */
    #[Route('/admin/categories', name: self::ROUTE_CATEGORIES)]
    public function index(Request $request): Response{
        $categories = $this->categorieRepository->findAll();
        
        $categoriesUtilisees = [];
        foreach ($categories as $c){
            $categoriesUtilisees[] = $this->categorieUtilisee($c->getId());
        }
        
        $categorie = new Categorie();
        $formCategorie = $this->createForm(CategorieType::class, $categorie);
        
        $formCategorie->handleRequest($request);
        if($formCategorie->isSubmitted() && $formCategorie->isValid() && !$this->categorieExiste($categorie->getName())){
            $this->categorieRepository->add($categorie);
            return $this->redirectToRoute(self::ROUTE_CATEGORIES);
        }
        
        return $this->render(self::LIEN_CATEGORIES, [
            'categories' => $categories,
            'formcategorie' => $formCategorie->createView(),
            'categoriesutilisees' => $categoriesUtilisees
        ]);
    }
    
    /**
     * Fonction permettant de supprimer une catégorie.
     * @Route("/admin/categories/suppr/{id}", name="admin.categorie.suppr")
     * @param int $id
     * @return Response
     */
    #[Route('/admin/categories/suppr/{id}', name: 'admin.categorie.suppr')]
    public function suppr(int $id): Response {
        $categorie = $this->categorieRepository->find($id);

        if (!$this->categorieUtilisee($id)){
            $this->categorieRepository->remove($categorie);
        }
        return $this->redirectToRoute(self::ROUTE_CATEGORIES);
    }
    
    
    /**
     * Fonction permettant de vérifier si une catégorie est déjà utilisée, ou non.
     * @param int $id
     * @return bool
     */
    public function categorieUtilisee(int $id): bool {
        $categorie = $this->categorieRepository->find($id);
        $formations = $this->formationRepository->findAllOrderBy('id', 'ASC');
        foreach ($formations as $f){
            $playlistCategories = $this->categorieRepository->findAllForOnePlaylist($f->getId());
            foreach ($playlistCategories as $pc){
                if ($pc->getName() == $categorie->getName()){
                    return true;
                }
            }
        }
        return false;
    }
    
    /**
     * Fonction permettant de vérifier qu'une catégorie existe déjà ou non.
     * @param string $name
     * @return bool
     */
    public function categorieExiste(string $name): bool {
        $categories = $this->categorieRepository->findAll();
        foreach ($categories as $c){
            if ($c->getName() == $name){
                return true;
            }
        }
        return false;
    }
}