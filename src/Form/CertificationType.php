<?php


namespace App\Form;

use App\Entity\Badge;
use App\Entity\Certification;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CertificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('badge', EntityType::class, [
                'class' => Badge::class,
                'choice_label' => 'nom',
            ])
            ->add('issuer', TextType::class)
            ->add('issuedAt', DateType::class, ['widget' => 'single_text'])
            ->add('proofUrl', UrlType::class, ['required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Certification::class]);
    }
}
