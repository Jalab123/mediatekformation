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
 * Description of AdminFormationsController
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
    
    const LIEN_CATEGORIES = "admin/admin.categories.html.twig";
    const ROUTE_CATEGORIES = "admin.categories";
    
    function __construct( 
            CategorieRepository $categorieRepository,
            FormationRepository $formationRespository) {
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRespository;
    }
    
    /**
     * @Route("/admin/categories", name="admin.categories")
     * @return Response
     */
    #[Route('/admin/categories', name: 'admin.categories')]
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
    
    #[Route('/admin/categories/suppr/{id}', name: 'admin.categorie.suppr')]
    public function suppr(int $id): Response {
        $categorie = $this->categorieRepository->find($id);

        if (!$this->categorieUtilisee($id)){
            $this->categorieRepository->remove($categorie);
        }
        return $this->redirectToRoute(self::ROUTE_CATEGORIES);
    }
    
    
    
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