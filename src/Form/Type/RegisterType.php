<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\DTO\RegistrationRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
                'attr' => [
                    'placeholder' => 'exemple@ehei.ac.ma',
                    'class' => 'form-control',
                ],
                'row_attr' => ['class' => 'mb-3'],
            ])
            ->add('fullName', TextType::class, [
                'label' => 'Nom complet',
                'attr' => [
                    'placeholder' => 'Votre nom et prénom',
                    'class' => 'form-control',
                ],
                'row_attr' => ['class' => 'mb-3'],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => [
                        'placeholder' => 'Votre mot de passe',
                        'class' => 'form-control',
                    ],
                    'help_html' => true,
                    'help' => '
                        <div class="alert alert-info mt-2">
                            <small>
                                <strong>Le mot de passe doit contenir :</strong>
                                <ul class="mb-0">
                                    <li>Au moins 8 caractères</li>
                                    <li>Au moins une majuscule</li>
                                    <li>Au moins une minuscule</li>
                                    <li>Au moins un chiffre</li>
                                    <li>Au moins un caractère spécial (@, -, _)</li>
                                </ul>
                            </small>
                        </div>
                    ',
                ],
                'second_options' => [
                    'label' => 'Confirmation du mot de passe',
                    'attr' => [
                        'placeholder' => 'Confirmez votre mot de passe',
                        'class' => 'form-control',
                    ],
                ],
                'row_attr' => ['class' => 'mb-3'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RegistrationRequest::class,
            'attr' => [
                'novalidate' => 'novalidate',
                'class' => 'needs-validation',
            ],
        ]);
    }
}
