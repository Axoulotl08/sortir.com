<?php

namespace App\Form;

use App\Data\SearchVilleCampus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchVilleCampusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomSearch', TextType::class, [
                'label' => 'Le nom contient : ',
                'attr' => [
                    'placeholder' => '🔍 '
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchVilleCampus::class,
            'method' => 'POST'
        ]);
    }
}
