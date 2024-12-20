<?php

namespace App\Repository;

use App\Entity\Playlist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository des playlists.
 * @extends ServiceEntityRepository<Playlist>
 */
class PlaylistRepository extends ServiceEntityRepository
{
    /**
     * Formations.
     */
    const FORMATIONS = 'p.formations';
    
    /**
     * Id.
     */
    const ID = 'p.id';
    
    /**
     * Nom.
     */
    const NOM = 'p.name';
    
    /**
     * Constructeur.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Playlist::class);
    }

    /**
     * Fonction permettant d'ajouter une playlist.
     * @param Playlist $entity
     * @return void
     */
    public function add(Playlist $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Fonction permettant de supprimer une playlist.
     * @param Playlist $entity
     * @return void
     */
    public function remove(Playlist $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
    
    /**
     * Retourne toutes les playlists triées sur le nom de la playlist
     * @param type $champ
     * @param type $ordre
     * @return Playlist[]
     */
    public function findAllOrderByName($ordre): array{
        return $this->createQueryBuilder('p')
                ->leftjoin(self::FORMATIONS, 'f')
                ->groupBy(self::ID)
                ->orderBy(self::NOM, $ordre)
                ->getQuery()
                ->getResult();       
    } 
    
    /**
     * Retourne toutes les playlists triées sur le nb de formations
     * @param type $champ
     * @param type $ordre
     * @return Playlist[]
     */
    public function findAllOrderByNbFormations($ordre): array{
        return $this->createQueryBuilder('p')
                ->leftjoin(self::FORMATIONS, 'f')
                ->groupBy(self::ID)
                ->orderBy('COUNT(f.id)', $ordre)
                ->getQuery()
                ->getResult();       
    } 
	
    /**
     * Enregistrements dont un champ contient une valeur
     * ou tous les enregistrements si la valeur est vide
     * @param type $champ
     * @param type $valeur
     * @param type $table si $champ dans une autre table
     * @return Playlist[]
     */
    public function findByContainValue($champ, $valeur, $table=""): array{
        if($valeur==""){
            return $this->findAllOrderByName('ASC');
        }    
        if($table==""){      
            return $this->createQueryBuilder('p')
                    ->leftjoin(self::FORMATIONS, 'f')
                    ->where('p.'.$champ.' LIKE :valeur')
                    ->setParameter('valeur', '%'.$valeur.'%')
                    ->groupBy(self::ID)
                    ->orderBy(self::NOM, 'ASC')
                    ->getQuery()
                    ->getResult();              
        }else{   
            return $this->createQueryBuilder('p')
                    ->leftjoin(self::FORMATIONS, 'f')
                    ->leftjoin('f.categories', 'c')
                    ->where('c.'.$champ.' LIKE :valeur')
                    ->setParameter('valeur', '%'.$valeur.'%')
                    ->groupBy(self::ID)
                    ->orderBy(self::NOM, 'ASC')
                    ->getQuery()
                    ->getResult();              
        }           
    }    
    
}
