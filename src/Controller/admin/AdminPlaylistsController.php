<?php

namespace App\Controller\admin;

use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur admin des playlists.
 *
 * @author pilou
 */
class AdminPlaylistsController extends AbstractController {
    
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
     * Lien vers la page des playlists.
     */
    const LIEN_PLAYLISTS = "admin/admin.playlists.html.twig";
    
    /**
     * Lien vers la route des playlists.
     */
    const ROUTE_PLAYLISTS = "admin.playlists";
    
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
     * @Route("/admin/playlists", name="admin.playlists")
     * @return Response
     */
    #[Route('/admin/playlists', name: 'admin.playlists')]
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
     * @Route("/admin/playlists/tri/{champ}/{ordre}", name="admin.playlists.sort")
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
    #[Route('/admin/playlists/tri/{champ}/{ordre}', name: 'admin.playlists.sort')]
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
     * @Route("/admin/playlists/recherche/{champ}/{table}", name="admin.playlists.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    #[Route('/admin/playlists/recherche/{champ}/{table}', name: 'admin.playlists.findallcontain')]
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
     * Fonction permettant de supprimer une playlist.
     * @Route("/admin/playlists/suppr/{id}", name="admin.playlist.suppr")
     * @param int $id
     * @return Response
     */
    #[Route('/admin/playlists/suppr/{id}', name: 'admin.playlist.suppr')]
    public function suppr(int $id): Response {
        $playlist = $this->playlistRepository->find($id);
        if ($playlist->getNbFormations() == 0){
            $this->playlistRepository->remove($playlist);
        }
        return $this->redirectToRoute(self::ROUTE_PLAYLISTS);
    }
    
    /**
     * Fonction permettant de modifier une playlist.
     * @Route("/admin/playlists/edit/{id}", name="admin.playlist.edit")
     * @param int $id
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/playlists/edit/{id}', name: 'admin.playlist.edit')]
    public function edit(int $id, Request $request): Response{
       $playlist = $this->playlistRepository->find($id);
       $formPlaylist = $this->createForm(PlaylistType::class, $playlist);
       $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);
       
       $formPlaylist->handleRequest($request);
       if($formPlaylist->isSubmitted() && $formPlaylist->isValid()){
           $this->playlistRepository->add($playlist);
           return $this->redirectToRoute(self::ROUTE_PLAYLISTS);
       }
       
       return $this->render("admin/admin.playlist.edit.html.twig", [
           'playlist' => $playlist,
           'formplaylist' => $formPlaylist->createView(),
           'playlistformations' => $playlistFormations
       ]);
    }
    
    /**
     * Fonction permettant d'ajouter une playlist.
     * @Route("/admin/playlists/ajout}", name="admin.playlist.ajout")
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/playlists/ajout', name: 'admin.playlist.ajout')]
    public function add(Request $request): Response{
       $playlist = new Playlist();
       $formPlaylist = $this->createForm(PlaylistType::class, $playlist);
        
       $formPlaylist->handleRequest($request);
       if($formPlaylist->isSubmitted() && $formPlaylist->isValid()){
           $this->playlistRepository->add($playlist);
           return $this->redirectToRoute(self::ROUTE_PLAYLISTS);
       }
       
       return $this->render("admin/admin.playlist.ajout.html.twig", [
           'playlist' => $playlist,
           'formplaylist' => $formPlaylist->createView()
       ]);
    }
}
