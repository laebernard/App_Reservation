<?php

namespace App\Form;

use App\Entity\Booking;
use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints\NotBlank;
use App\Form\UserType;


class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'required' => true,
                'widget' => 'single_text',
                "attr" => [
                    "class" => "form-control datepicker"
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir une heure valide'])
                ]
            ])
          
            ->add('heure', TimeType::class, [
                'required' => true,
                "attr" => [
                    "class" => "form-control datepicker"
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir une heure valide'])
                ]
            ])
            ->add('service', EntityType::class, [
                'label' => "Service",
                'class' => Service::class,
                'choice_label' => 'nom',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.nom', 'ASC');
                },
                'required' => true,
            ])
            ->add('user', UserType::class, [
                'label' => "Informations de l'utilisateur",
                'required' => false 
            ])
         
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);

    }
}
