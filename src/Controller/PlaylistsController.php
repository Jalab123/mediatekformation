<?php
namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur des playlists.
 *
 * @author emds
 */
class PlaylistsController extends AbstractController {
    
    /**
     * Repository des playlists.
     * @var PlaylistRepository
     */
    private $playlistRepository;
    
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
    const LIEN_PLAYLISTS = "pages/playlists.html.twig";
    
    /**
     * Constructeur.
     * @param PlaylistRepository $playlistRepository
     * @param CategorieRepository $categorieRepository
     * @param FormationRepository $formationRespository
     */
    function __construct(PlaylistRepository $playlistRepository, 
            CategorieRepository $categorieRepository,
            FormationRepository $formationRespository) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRespository;
    }
    
    /**
     * Fonction exécutée lors du chargement de la page.
     * @Route("/playlists", name="playlists")
     * @return Response
     */
    #[Route('/playlists', name: 'playlists')]
    public function index(): Response{
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::LIEN_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories            
        ]);
    }
    
    /**
     * Fonction permettant de trier sur un certain champ et un certain ordre.
     * @Route("/playlists/tri/{champ}/{ordre}", name="playlists.sort")
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
    #[Route('/playlists/tri/{champ}/{ordre}', name: 'playlists.sort')]
    public function sort($champ, $ordre): Response{
        if ($champ == "name"){
            $playlists = $this->playlistRepository->findAllOrderByName($ordre);
        } elseif ($champ == "nbformations"){
            $playlists = $this->playlistRepository->findAllOrderByNbFormations($ordre);
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::LIEN_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories            
        ]);
    }          

    /**
     * Fonction permettant de filtrer sur un certain champ et une certaine table.
     * @Route("/playlists/recherche/{champ}/{table}", name="playlists.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    #[Route('/playlists/recherche/{champ}/{table}', name: 'playlists.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::LIEN_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories,            
            'valeur' => $valeur,
            'table' => $table
        ]);
    }  

    /**
     * Fonction permettant l'affichage d'une playlist.
     * @Route("/playlists/formation/{id}", name="playlists.showone")
     * @param type $id
     * @return Response
     */
    #[Route('/playlists/playlist/{id}', name: 'playlists.showone')]
    public function showOne($id): Response{
        $playlist = $this->playlistRepository->find($id);
        $playlistCategories = $this->categorieRepository->findAllForOnePlaylist($id);
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);
        return $this->render("pages/playlist.html.twig", [
            'playlist' => $playlist,
            'playlistcategories' => $playlistCategories,
            'playlistformations' => $playlistFormations
        ]);        
    }       
    
}
