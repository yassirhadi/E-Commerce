<?php
// src/Service/InscriptionService.php

namespace App\Service;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Service gérant l'inscription des utilisateurs.
 * Respecte SRP : une seule responsabilité — inscrire un utilisateur.
 * Respecte DIP : dépend des abstractions (interfaces).
 */
class InscriptionService
{
    public function __construct(
        private readonly EntityManagerInterface      $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {}

    public function inscrire(Utilisateur $utilisateur, string $motDePasseBrut): void
    {
        // Hachage du mot de passe
        $motDePasseHache = $this->passwordHasher->hashPassword(
            $utilisateur,
            $motDePasseBrut
        );

        $utilisateur->setPassword($motDePasseHache);

        $this->entityManager->persist($utilisateur);
        $this->entityManager->flush();
    }
}
