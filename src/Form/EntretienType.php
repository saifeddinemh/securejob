<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntretienType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $questions = $options['questions'] ?? [];

        foreach ($questions['technique'] as $index => $question) {
            $builder->add("question_technique_$index", TextareaType::class, [
                'label' => $question,
                'required' => false,
                'attr' => ['rows' => 3]
            ]);
        }

        foreach ($questions['comportementale'] as $index => $question) {
            $builder->add("question_comportementale_$index", TextareaType::class, [
                'label' => $question,
                'required' => false,
                'attr' => ['rows' => 4]
            ]);
        }

        $builder->add('submit', SubmitType::class, [
            'label' => 'Soumettre les réponses pour évaluation',
            'attr' => ['class' => 'btn btn-success']
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'questions' => []
        ]);
    }
}
