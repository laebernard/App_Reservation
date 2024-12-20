<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Veuillez saisir un nom'])
            ]
        ])
        ->add('prenom', TextType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Veuillez saisir un prénom'])
            ]
        ])
        ->add('email', EmailType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Veuillez saisir un email'])
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
