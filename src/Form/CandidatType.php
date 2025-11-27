<?php

namespace App\Form;

use App\Entity\Candidat;
use App\Entity\Langue;
use App\Entity\Badge;
use App\Entity\Competence;
use App\Form\ExperienceType;
use App\Form\FormationType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CandidatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Infos personnelles
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('email', EmailType::class)
            ->add('telephone', TextType::class, ['required' => false])
            ->add('dateNaissance', DateType::class, ['widget' => 'single_text', 'required' => false])
            ->add('adresse', TextType::class, ['required' => false])
            ->add('description', TextareaType::class, ['required' => false])
            ->add('cvFile', FileType::class, ['mapped' => false, 'required' => false])
            ->add('photoFile', FileType::class, ['mapped' => false, 'required' => false])

            // Expériences
            ->add('experiences', CollectionType::class, [
                'entry_type' => ExperienceType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])

            // Formations
            ->add('formations', CollectionType::class, [
                'entry_type' => FormationType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])

            // Langues (sélection d’entités existantes)
            ->add('langues', EntityType::class, [
                'class' => Langue::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ])

            // Badges (sélection d’entités existantes)
            ->add('badges', EntityType::class, [
                'class' => Badge::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ])

            // Compétences (sélection d’entités existantes)
            ->add('competences', EntityType::class, [
                'class' => Competence::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Candidat::class,
        ]);
    }
}
