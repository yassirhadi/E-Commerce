<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class PasswordField extends Constraint
{
    public string $message = 'Le mot de passe n\'est pas valide. Il doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial (@, -, _).';

    public function __construct(
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct([], $groups, $payload);

        if ($message !== null) {
            $this->message = $message;
        }
    }

    public function validatedBy(): string
    {
        return static::class . 'Validator';
    }
}
