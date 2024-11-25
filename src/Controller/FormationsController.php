<?php
namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur des formations.
 *
 * @author emds
 */
class FormationsController extends AbstractController {

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
    const LIEN_FORMATIONS = "pages/formations.html.twig";
    
    /**
     * Constructeur.
     * @param FormationRepository $formationRepository
     * @param CategorieRepository $categorieRepositoryC
     */
    function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository= $categorieRepository;
    }
    
    /**
     * Fonction exécutée lors du chargement de la page.
     * @Route("/formations", name="formations")
     * @return Response
     */
    #[Route('/formations', name: 'formations')]
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
     * @Route("/formations/tri/{champ}/{ordre}/{table}", name="formations.sort")
     * @param type $champ
     * @param type $ordre
     * @param type $table
     * @return Response
     */
    #[Route('/formations/tri/{champ}/{ordre}/{table}', name: 'formations.sort')]
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
     * @Route("/formations/recherche/{champ}/{table}", name="formations.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    #[Route('/formations/recherche/{champ}/{table}', name: 'formations.findallcontain')]
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
     * Fonction permettant l'affichage d'une formation.
     * @Route("/formations/formation/{id}", name="formations.showone")
     * @param type $id
     * @return Response
     */
    #[Route('/formations/formation/{id}', name: 'formations.showone')]
    public function showOne($id): Response{
        $formation = $this->formationRepository->find($id);
        return $this->render("pages/formation.html.twig", [
            'formation' => $formation
        ]);        
    }   
    
}
