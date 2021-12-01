<?php

namespace App\Form;

use App\Entity\Episode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EpisodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'required' => false,
                'label'=> 'Nom de l\'episode',
                'attr' => [
                    'placeholder' => 'Taper le nom ici...'
                ]
            ])
            ->add('pathToVideo',TextType::class,[
                'required' => false,
                'label' => 'Code youtube de la video',
                'attr' => [
                    'placeholder' => 'Taper le code ici...'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Episode::class,
        ]);
    }
}
