<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Controller de connexion
 * 
 * @author Saatsa franklin Blerio <saatsafranklin@gmail.com>
 * @description Analyste programmeur/developpeur full-strack
 *
 */
#[Route('/', name: 'app_')]
class SecurityController extends AbstractController
{
    /**
     * Action permettant de connexion d'un utilisateur
     * 
     * @param mixed|AuthenticationUtils $authenticationUtils, 
     * @return Response
     */
    #[Route(path: '/', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // recuperer les erreurs de connexions s'ils existent
        $error = $authenticationUtils->getLastAuthenticationError();

        // recuperer le dernier nom d'utilisateur entrer par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * Action permettant de deconnecter un utilisateur
     * 
     * @param mixed|AuthenticationUtils $authenticationUtils, 
     * @return Response
     */
    #[Route(path: '/logout', name: 'logout')]
    public function logout(): Response
    {
        //Notification de deconnexion
        $this->addFlash(
            'pink',
            'Deconnexion éffectuer avec success!'
        );
        return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
    }
}
