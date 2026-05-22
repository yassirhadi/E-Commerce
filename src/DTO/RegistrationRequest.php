<?php

declare(strict_types=1);

namespace App\DTO;

use App\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationRequest
{
    #[Assert\Email(message: 'Cette adresse email {{ value }} est invalide.')]
    #[AppAssert\RequiredField]
    #[Assert\Length(max: 180, maxMessage: 'L\'email ne peut pas dépasser {{ limit }} caractères.')]
    private ?string $email = null;

    #[AppAssert\RequiredField]
    #[AppAssert\PasswordField('Le mot de passe doit contenir au minimum 8 caractères, avec au moins une majuscule, une minuscule, un chiffre et un caractère spécial parmi @, - ou _')]
    private ?string $password = null;

    #[AppAssert\RequiredField]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'Le nom complet doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'Le nom complet ne peut pas dépasser {{ limit }} caractères.'
    )]
    private ?string $fullName = null;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): void
    {
        $this->fullName = $fullName;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function __toString(): string
    {
        return sprintf('RegistrationRequest[email=%s, fullName=%s]',
            $this->email ?? 'null',
            $this->fullName ?? 'null'
        );
    }
}
