<?php
namespace App\Controller;

use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur de la page d'accueil.
 *
 * @author emds
 */
class AccueilController extends AbstractController{
    
    /**
     * Repository des formations.
     * @var FormationRepository
     */
    private $repository;
    
    /**
     * Constructeur.
     * @param FormationRepository $repository
     */
    public function __construct(FormationRepository $repository) {
        $this->repository = $repository;
    }   
    
    /**
     * Fonction exécutée lors du chargement de la page.
     * @Route("/", name="accueil")
     * @return Response
     */
    #[Route('/', name: 'accueil')]
    public function index(): Response{
        $formations = $this->repository->findAllLasted(2);
        return $this->render("pages/accueil.html.twig", [
            'formations' => $formations
        ]); 
    }
    
    /**
     * Fonction permettant l'affichage des CGU.
     * @Route("/cgu", name="cgu")
     * @return Response
     */
    #[Route('/cgu', name: 'cgu')]
    public function cgu(): Response{
        return $this->render("pages/cgu.html.twig"); 
    }
}
