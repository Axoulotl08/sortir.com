<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('dateHeureDebut', DateTimeType::class, [
                'html5' => true,
                'widget' => 'single_text',
                'label' => false,
                'required' => false,
            ])
            ->add('duree')
            ->add('dateLimiteInscription', DateType::class, [
                'html5' => true,
                'widget' => 'single_text',
                'label' => false,
                'required' => false,
            ])
            ->add('nbInscriptionsMax')
            ->add('infosSortie')
            ->add('lieu', EntityType::class, [
                'label' => 'etat',
                'choice_label' => 'nom',
                'required' => false,
                'class' => Lieu::class,
                'expanded' => false,
                'multiple' => false,
            ] )
            /*
            ->add('etat', EntityType::class, [
                'label' => 'etat',
                'choice_label' => 'libelle',
                'required' => false,
                'class' => Etat::class,
                'expanded' => false,
                'multiple' => false,
            ] )
            ->add('siteOrganisateur', EntityType::class, [
                'label' => 'site Organisateur',
                'choice_label' => 'nom',
                'required' => false,
                'class' => Campus::class,
                'expanded' => false,
                'multiple' => false,
                'empty_data' => $options,
            ])

            ->add('organisateur')
            ->add('inscrits')
            */
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
