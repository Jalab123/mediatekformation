<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Catégorie.
 */
#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    /**
     * Id de la catégorie.
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /*
     * Nom de la catégorie.
     */
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $name = null;

    /**
     * Liste des formations.
     * @var Collection<int, Formation>
     */
    #[ORM\ManyToMany(targetEntity: Formation::class, mappedBy: 'categories')]
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
            $formation->addCategory($this);
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
        if ($this->formations->removeElement($formation)) {
            $formation->removeCategory($this);
        }

        return $this;
    }
}
