<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class PasswordFieldValidator extends ConstraintValidator
{
    private const PASSWORD_PATTERN = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@\-_]).{8,}$/';

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof PasswordField) {
            throw new UnexpectedTypeException($constraint, PasswordField::class);
        }

        // Si la valeur est null ou vide, ne rien faire (la contrainte RequiredField s'en occupe)
        if (null === $value || '' === $value) {
            return;
        }

        // Vérifier que c'est bien une chaîne de caractères
        if (!\is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        // Vérifier le format du mot de passe
        if (\preg_match(self::PASSWORD_PATTERN, $value) !== 1) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->addViolation();
        }
    }
}
