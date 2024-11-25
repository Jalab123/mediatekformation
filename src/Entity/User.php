<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Utilisateur.
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * Id de l'utilisateur.
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Email de l'utilisateur.
     * @var string|null
     */
    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * Rôles de l'utilisateur.
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * Mot de passe de l'utilisateur.
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * Id Keycloak de l'utilisateur.
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $keycloakId = null;

    /**
     * Getter sur l'id.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter sur l'email.
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Setter sur l'email.
     * @param string $email
     * @return static
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Un identifiant visuel qui représente cet utilisateur.
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * Getter sur les roles.
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Setter sur les rôles.
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Getter sur le mot de passe.
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Setter sur le mot de passe.
     * @param string $password
     * @return static
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Fonction permettant de supprimer les informations d'identification.
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * Getter sur l'id Keycloak.
     * @return string|null
     */
    public function getKeycloakId(): ?string
    {
        return $this->keycloakId;
    }

    /**
     * Setter sur l'id Keycloak.
     * @param string|null $keycloakId
     * @return static
     */
    public function setKeycloakId(?string $keycloakId): static
    {
        $this->keycloakId = $keycloakId;

        return $this;
    }
}
