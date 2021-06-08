<?php

namespace App\Form;

use App\Data\ImportCSV;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class ImportCSVType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('csvFileName', FileType::class, [
                'label' => 'Fichier CSV',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ImportCSV::class,
            'method' => 'POST',
            'csrf_protection' => true
        ]);
    }
}
