<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

/**
 * Classe permettant de gérer l'authentification avec Keycloak.
 *
 * @author pilou
 */
class KeycloakAuthenticator extends OAuth2Authenticator implements AuthenticationEntryPointInterface {
    
    /**
     * Client.
     * @var type
     */
    private $clientRegistry;
    
    /**
     * Entité.
     * @var type
     */
    private $entityManager;
    
    /**
     * Routeur.
     * @var type
     */
    private $router;
    
    /**
     * Constructeur.
     * @param ClientRegistry $clientRegistry
     * @param EntityManagerInterface $entityManager
     * @param RouterInterface $router
     */
    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $entityManager, RouterInterface $router) {
        $this->clientRegistry = $clientRegistry;
        $this->entityManager = $entityManager;
        $this->router = $router;
    }
    
    /**
     * Fonction permettant l'authentification.
     * @param Request $request
     * @return Passport
     */
    public function authenticate(Request $request): Passport {
        $client = $this->clientRegistry->getClient('keycloak');
        $accessToken = $this->fetchAccessToken($client);
        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function() use ($accessToken, $client){
                /** @var KeycloakUser $keycloakUser */
                $keycloakUser = $client->fetchUserFromToken($accessToken);
                // 1) recherche du user dans la BDD à partir de son id Keycloak
                $existingUser = $this->entityManager
                        ->getRepository(User::class)
                        ->findOneBy(['keycloakId' => $keycloakUser->getId()]);
                if ($existingUser){
                    return $existingUser;
                }
                // 2) le user existe mais n'est pas encore connecté avec Keycloak
                $email = $keycloakUser->getEmail();
                /** @var User $userInDatabase */
                $userInDatabase = $this->entityManager
                        ->getRepository(User::class)
                        ->findOneBy(['email' => $email]);
                if ($userInDatabase){
                    $userInDatabase->setKeyCloakId($keycloakUser->getId());
                    $this->entityManager->persist($userInDatabase);
                    $this->etntiyManager->flush();
                    return $userInDatabase;
                }
                // 3) le user n'existe pas encore dans la BDD
                $user = new User();
                $user->setKeycloakId(($keycloakUser->getId()));
                $user->setEmail($keycloakUser->getEmail());
                $user->setPassword("");
                $user->setRoles(['ROLE_ADMIN']);
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                return $user;
            })
        );
    }

    /**
     * Fonction permettant de gérer un échec de connexion.
     * @param Request $request
     * @param AuthenticationException $exception
     * @return Response|null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response {
        $message = strtr($exception->getMessageKey(), $exception->getMessageDAta());
        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Fonction permettant de gérer un succès de connexion.
     * @param Request $request
     * @param TokenInterface $token
     * @param string $firewallName
     * @return Response|null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response {
        $targetUrl = $this->router->generate('admin.formations');
        return new RedirectResponse($targetUrl);
    }

    /**
     * Fonctionnant permettant de gérer la connexion au compte.
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @return Response
     */
    public function start(Request $request, ?AuthenticationException $authException = null): Response {
        return new RedirectResponse(
                '/oauth/login',
                Response::HTTP_TEMPORARY_REDIRECT
        );
    }

    /**
     * Fonction permettant le support.
     * @param Request $request
     * @return bool|null
     */
    public function supports(Request $request): ?bool {
        return $request->attributes->get('_route') === 'oauth_check';
    }

}
