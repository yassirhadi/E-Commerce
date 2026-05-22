<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Compound;
use Symfony\Component\Validator\Constraints as Assert;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class RequiredField extends Compound
{
    protected function getConstraints(array $options): array
    {
        return [
            new Assert\NotNull(message: 'Ce champ est obligatoire.'),
            new Assert\NotBlank(message: 'Ce champ ne doit pas être vide.'),
        ];
    }
}
