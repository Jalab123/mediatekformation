<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Contrôleur de la connexion.
 */
class OAuthController extends AbstractController
{
    /**
     * Fonction exécutée lors du chargement de la page.
     * @Route("/oauth/login", name="oauth_login")
     * @param ClientRegistry $clientRegistry
     * @return RedirectResponse
     */
    #[Route('/oauth/login', name: 'oauth_login')]
    public function index(ClientRegistry $clientRegistry): RedirectResponse
    {
        return $clientRegistry->getClient('keycloak')->redirect();
    }
    
    /**
     * Fonction permettant la vérification.
     * @Route("/oauth/callback", name="oauth_check")
     * @param Request $request
     * @param ClientRegistry $clientRegistry
     */
    #[Route('/oauth/callback', name: 'oauth_check')]
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry){
        
    }
    
    /**
     * Fonction permettant la déconnexion.
     * @Route("/logout", name="logout")
     */
    #[Route('/logout', name: 'logout')]
    public function logout(){
        
    }
}
