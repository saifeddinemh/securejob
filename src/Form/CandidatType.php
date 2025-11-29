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
            // -------------------------------------------------------------
            // INFOS PERSONNELLES
            // -------------------------------------------------------------
            ->add('nom', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
            ])
            ->add('telephone', TextType::class, [
                'required' => false,
                'label' => 'Téléphone',
            ])
            ->add('dateNaissance', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Date de naissance',
            ])
            ->add('adresse', TextType::class, [
                'required' => false,
                'label' => 'Adresse',
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Description / Bio',
            ])

            // -------------------------------------------------------------
            // UPLOADS
            // -------------------------------------------------------------
            ->add('cvFile', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'CV (PDF)',
            ])
            ->add('photoFile', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Photo de profil',
            ])

            // -------------------------------------------------------------
            // EXPERIENCES PROFESSIONNELLES
            // -------------------------------------------------------------
            ->add('experiences', CollectionType::class, [
                'entry_type' => ExperienceType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'Expériences',
            ])

            // -------------------------------------------------------------
            // FORMATIONS
            // -------------------------------------------------------------
            ->add('formations', CollectionType::class, [
                'entry_type' => FormationType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'Formations',
            ])

            // -------------------------------------------------------------
            // LANGUES
            // -------------------------------------------------------------
            ->add('langues', EntityType::class, [
                'class' => Langue::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'label' => 'Langues parlées',
            ])

            // -------------------------------------------------------------
            // BADGES (Certifications)
            // -------------------------------------------------------------
            ->add('badges', EntityType::class, [
                'class' => Badge::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'label' => 'Badges & Certifications',
            ])

            // -------------------------------------------------------------
            // COMPÉTENCES
            // -------------------------------------------------------------
            ->add('competences', EntityType::class, [
                'class' => Competence::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'label' => 'Compétences',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Candidat::class,
        ]);
    }
}
