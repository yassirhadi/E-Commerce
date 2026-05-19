<?php
// src/Controller/AuthentificationController.php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\InscriptionType;
use App\Service\InscriptionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthentificationController extends AbstractController
{
    #[Route('/connexion', name: 'app_connexion')]
    public function connexion(AuthenticationUtils $authenticationUtils): Response
    {
        // Rediriger si déjà connecté
        if ($this->getUser()) {
            return $this->redirectToRoute('app_profil');
        }

        $erreur        = $authenticationUtils->getLastAuthenticationError();
        $dernierEmail  = $authenticationUtils->getLastUsername();

        return $this->render('auth/connexion.html.twig', [
            'dernierEmail' => $dernierEmail,
            'erreur'       => $erreur,
        ]);
    }

    #[Route('/inscription', name: 'app_inscription')]
    public function inscription(
        Request            $request,
        InscriptionService $inscriptionService
    ): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_profil');
        }

        $utilisateur = new Utilisateur();
        $formulaire  = $this->createForm(InscriptionType::class, $utilisateur);
        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            $motDePasseBrut = $formulaire->get('plainPassword')->getData();
            $inscriptionService->inscrire($utilisateur, $motDePasseBrut);

            $this->addFlash('success', 'Compte créé avec succès ! Connectez-vous.');

            return $this->redirectToRoute('app_connexion');
        }

        return $this->render('auth/inscription.html.twig', [
            'formulaire' => $formulaire->createView(),
        ]);
    }

    #[Route('/deconnexion', name: 'app_deconnexion')]
    public function deconnexion(): void
    {
        // Géré automatiquement par Symfony Security
    }

    #[Route('/profil', name: 'app_profil')]
    public function profil(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('auth/profil.html.twig' , [
            'commandes' => []
        ]);
    }
}
