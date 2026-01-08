<?php

namespace App\Form;

use App\Entity\Mission;
use App\Entity\Competence;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, ['label' => 'Titre de la mission'])
            ->add('description', TextareaType::class, ['label' => 'Description détaillée'])
            ->add('duree', TextType::class, ['label' => 'Durée estimée (ex: 3 jours, 1 mois)'])
            ->add('budget', NumberType::class, ['label' => 'Budget (€)'])
            ->add('competencesRequises', EntityType::class, [
                'class' => Competence::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Compétences requises'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mission::class,
        ]);
    }
}
