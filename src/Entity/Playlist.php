<?php

namespace App\Entity;

use App\Repository\PlaylistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Playlist.
 */
#[ORM\Entity(repositoryClass: PlaylistRepository::class)]
class Playlist
{
    /**
     * Id de la playlist.
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Nom de la playlist.
     * @var string|null
     */
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $name = null;

    /**
     * Description de la playlist.
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * Liste des formations.
     * @var Collection<int, Formation>
     */
    #[ORM\OneToMany(targetEntity: Formation::class, mappedBy: 'playlist')]
    private Collection $formations;

    /**
     * Constructeur.
     */
    public function __construct()
    {
        $this->formations = new ArrayCollection();
    }

    /**
     * Getter sur l'id.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter sur le nom.
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Setter sur le nom.
     * @param string|null $name
     * @return static
     */
    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Getter sur la description.
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Setter sur la description.
     * @param string|null $description
     * @return static
     */
    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Getter sur les formations.
     * @return Collection<int, Formation>
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    /**
     * Fonction permettant d'ajouter une formation.
     * @param Formation $formation
     * @return static
     */
    public function addFormation(Formation $formation): static
    {
        if (!$this->formations->contains($formation)) {
            $this->formations->add($formation);
            $formation->setPlaylist($this);
        }

        return $this;
    }

    /**
     * Fonction permettant de supprimer une formation.
     * @param Formation $formation
     * @return static
     */
    public function removeFormation(Formation $formation): static
    {
        if ($this->formations->removeElement($formation) && ($formation->getPlaylist() === $this)) {
            // set the owning side to null (unless already changed)
            $formation->setPlaylist(null);
        }

        return $this;
    }
    
    /**
     * Getter sur les catégories de la playlist.
     * @return Collection<int, string>
     */
    public function getCategoriesPlaylist() : Collection
    {
        $categories = new ArrayCollection();
        foreach($this->formations as $formation){
            $categoriesFormation = $formation->getCategories();
            foreach($categoriesFormation as $categorieFormation){
                if(!$categories->contains($categorieFormation->getName())){
                    $categories[] = $categorieFormation->getName();
                }
            }
        }
        return $categories;
    }
    
    /**
     * Getter sur le nombre de formations.
     * @return int
     */
    public function getNbFormations() : int
    {
        return count($this->formations);
    }
        
}