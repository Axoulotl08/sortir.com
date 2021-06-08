<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

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
            ->add('infosSortie')/*
            ->add('ville', EntityType::class, [
                'label'=>'ville',
                'mapped' => false,
                'choice_label' => 'nom',
                'class' => Ville::class
            ])
            ->add('lieu', EntityType::class, [
                'label' => 'lieu',
                'choice_label' => 'nom',
                'required' => false,
                'class' => Lieu::class,
                'expanded' => false,
                'multiple' => false,
                'choices' => $choix = null,
            ])*/
        ->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'))
        ->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'))


        ;
    }

    protected function ajouterChamps(FormInterface $form, Ville $ville = null){
        $form->add('ville', EntityType::class, [
            'label'=>'ville',
            'mapped' => false,
            'choice_label' => 'nom',
            'class' => Ville::class
        ]);

        $lieu = [];

        if ($ville){
            $lieuRepository = $this->entityManager->getRepository('App:Lieu');
            $idVille =$ville->getId();
            $lieu = $lieuRepository->lieuSelonVille($idVille);
        }

        $form->add('lieu', EntityType::class, [
            'label' => 'lieu',
            'choice_label' => 'nom',
            'required' => false,
            'class' => Lieu::class,
            'expanded' => false,
            'multiple' => false,
            'choices' => $lieu,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }

    public function onPreSubmit(FormEvent $event)
    {
        $formulaire = $event->getForm();
        $donnees = $event->getData();

        $villes = $this->entityManager
            ->getRepository('App:Ville')->find($donnees['ville']);

        $this->ajouterChamps($formulaire, $villes);

    }

    function onPreSetData(FormEvent $event){
        $sortie = $event->getData();
        $formulaire = $event->getForm();

        $villes = $sortie->getLieu()->getVille() ? $sortie->getLieu()->getVille() : null;
        $this->ajouterChamps($formulaire, $villes);
    }
}
