<?php


namespace App\Form;


use App\Data\searchData;
use App\Entity\Campus;
use App\Entity\Participant;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{



    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //dump($options);
        $builder
            ->add('campus', EntityType::class, [
                'label' => 'Campus',
                'choice_label' => 'nom',
                'required' => false,
                'class' => Campus::class,
                'expanded' => false,
                'multiple' => false,
                'empty_data' => $options,
            ])
            ->add('titleSearch', TextType::class, [
                'label' => 'Le nom de la sortie contient',
                'required' => false,
                'attr' => [
                    'placeholder' => '🔍 search'
                ]
            ])
            ->add('dateIntervalDebut', DateType::class, [
                'html5' => true,
                'widget' => 'single_text',
                'label' => false,
                'required' => false,
            ])
            ->add('dateIntervalFin', DateType::class, [
                'html5' => true,
                'widget' => 'single_text',
                'label' => false,
                'required' => false,
            ])
            ->add('organisateur', CheckboxType::class, [
                'label' => 'Sorties dont je suis l organisateur/trice',
                'required' => false,
            ])
            ->add('estInscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je suis inscrit/e',
                'required' => false,
            ])
            ->add('nEstPasInscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je ne suis pas inscrit/e',
                'required' => false,
            ])
            ->add('passee', CheckboxType::class, [
                'label' => 'Sorties passées',
                'required' => false,
            ])
            ->add('particiantid', HiddenType::class)
        ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => searchData::class,
            'method' => 'GET',
            'csrf_protection' => true
        ]);
    }

    public function getBlockPrefix()
    {
        return '';

    }
}
