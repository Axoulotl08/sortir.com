<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AjouterSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la sortie :'
            ])
            ->add('dateHeureDebut', DateTimeType::class, [
                'html5' => true,
                'widget' => 'single_text',
                'label' => 'Date et heure de début :'
            ])
            ->add('duree', IntegerType::class, [
                'label' => 'Durée :'
            ])
            ->add('dateLimiteInscription', DateType::class, [
                'html5' => true,
                'widget' => 'single_text',
                'label' => 'Date limite d\'inscription :'
            ])
            ->add('nbInscriptionsMax', IntegerType::class, [
                'label' => 'Nombre de place'
            ])
            ->add('infosSortie', TextareaType::class, [
                'label' => 'Description et infos :'
            ])
            ->add('ville', EntityType::class, [
                'mapped' => false,
                'class' => Lieu::class,
                'label' => 'Ville :'
            ])
            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'label' => 'Lieu :'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
