<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Formation.
 */
#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{

    /**
     * Début de chemin vers les images
     */
    private const CHEMIN_IMAGE = "https://i.ytimg.com/vi/";
        
    /**
     * Id de la formation.
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Date de publication de la formation.
     * @var \DateTimeInterface|null
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\LessThanOrEqual("today")]
    private ?\DateTimeInterface $publishedAt = null;

    /**
     * Titre de la formation.
     * @var string|null
     */
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $title = null;

    /**
     * Description de la formation.
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * Id de la vidéo de la formation.
     * @var string|null
     */
    #[ORM\Column(length: 20, nullable: true)]
    private ?string $videoId = null;

    /**
     * Playlist de la formation.
     * @var Playlist|null
     */
    #[ORM\ManyToOne(inversedBy: 'formations')]
    private ?Playlist $playlist = null;

    /**
     * Liste des catégories.
     * @var Collection<int, Categorie>
     */
    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'formations')]
    private Collection $categories;

    /**
     * Constructeur.
     */
    public function __construct()
    {
        $this->categories = new ArrayCollection();
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
     * Getter sur la date de publication.
     * @return \DateTimeInterface|null
     */
    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    /**
     * Setter sur la date de publication
     * @param \DateTimeInterface|null $publishedAt
     * @return static
     */
    public function setPublishedAt(?\DateTimeInterface $publishedAt): static
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * Getter permettant d'obtenir la date de publication en format string.
     * @return string
     */
    public function getPublishedAtString(): string {
        if($this->publishedAt == null){
            return "";
        }
        return $this->publishedAt->format('d/m/Y');     
    }      
    
    /**
     * Getter sur le titre.
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Setter sur le titre.
     * @param string|null $title
     * @return static
     */
    public function setTitle(?string $title): static
    {
        $this->title = $title;

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
     * Getter sur l'id de la vidéo.
     * @return string|null
     */
    public function getVideoId(): ?string
    {
        return $this->videoId;
    }

    /**
     * Setter sur l'id de la vidéo.
     * @param string|null $videoId
     * @return static
     */
    public function setVideoId(?string $videoId): static
    {
        $this->videoId = $videoId;

        return $this;
    }

    /**
     * Getter sur la miniature.
     * @return string|null
     */
    public function getMiniature(): ?string
    {
        return self::CHEMIN_IMAGE.$this->videoId."/default.jpg";
    }

    /**
     * Getter sur l'image.
     * @return string|null
     */
    public function getPicture(): ?string
    {
        return self::CHEMIN_IMAGE.$this->videoId."/hqdefault.jpg";
    }
    
    /**
     * Getter sur la playlist.
     * @return playlist|null
     */
    public function getPlaylist(): ?playlist
    {
        return $this->playlist;
    }

    /**
     * Setter sur la playlist.
     * @param Playlist|null $playlist
     * @return static
     */
    public function setPlaylist(?Playlist $playlist): static
    {
        $this->playlist = $playlist;

        return $this;
    }

    /**
     * Getter sur les catégories.
     * @return Collection<int, Categorie>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    /**
     * Fonction permettant d'ajouter une catégorie.
     * @param Categorie $category
     * @return static
     */
    public function addCategory(Categorie $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    /**
     * Fonction permettant de supprimer une catégorie.
     * @param Categorie $category
     * @return static
     */
    public function removeCategory(Categorie $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }
}
