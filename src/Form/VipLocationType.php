<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Vehicle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class VipLocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('start_date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début',
            ])
            ->add('end_date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin',
            ]);

        if ($options['include_vehicle']) {
            $builder->add('vehicle', EntityType::class, [
                'class' => Vehicle::class,
                'choice_label' => 'model',
                'multiple' => true,
                'expanded' => false,
                'by_reference' => false,
                'label' => 'Véhicule(s)',
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
            'include_vehicle' => true,
        ]);
    }
}
