<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\RegistrationRequest;
use App\Form\Type\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function index(Request $request): Response
    {
        $registrationDTO = new RegistrationRequest();
        $registerForm = $this->createForm(RegisterType::class, $registrationDTO);

        $registerForm->handleRequest($request);

        if ($registerForm->isSubmitted()) {
            if ($registerForm->isValid()) {
                // Récupérer les données validées
                $data = $registerForm->getData();

                $this->addFlash('success', '✅ Inscription réussie ! Les données sont valides.');

                // Pour le test, afficher les données
                return $this->render('register/success.html.twig', [
                    'email' => $data->getEmail(),
                    'fullName' => $data->getFullName(),
                ]);
            } else {
                $this->addFlash('error', '❌ Veuillez corriger les erreurs dans le formulaire.');
            }
        }

        return $this->render('register/index.html.twig', [
            'registerForm' => $registerForm->createView(),
        ]);
    }

    #[Route('/register/test', name: 'app_register_test')]
    public function test(): Response
    {
        // Page de test pour vérifier que tout fonctionne
        return $this->render('register/test.html.twig');
    }
}
