<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Participant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom :'
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom : '
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Téléphone :'
            ])
            ->add('mail', EmailType::class, [
                'label' => 'Email'
            ])

            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'nom',
                'placeholder' => 'Sélectionnez le campus'
            ])
            ->add('administrateur', CheckboxType::class, [
                'label' => 'Administrateur ?',
                'required' => false
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'label' => 'Photo :'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
