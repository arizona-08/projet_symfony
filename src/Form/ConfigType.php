<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\Config;
use App\Entity\Kit;
use App\Entity\Vehicle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('vehicle', EntityType::class, [
                'class' => Vehicle::class,
                'required' => true,
                'choice_label' => function(Vehicle $vehicle) {
                    return sprintf('%s - %s (%s)',
                        $vehicle->getMarque(),
                        $vehicle->getModel(),
                        $vehicle instanceof Car ? 'Voiture' : 'Moto'
                    );
                },
                'placeholder' => 'Choisir un véhicule',
                'group_by' => function(Vehicle $vehicle) {
                    return $vehicle instanceof Car ? 'Voitures' : 'Motos';
                }
            ])
            ->add('kit', EntityType::class, [
                'class' => Kit::class,
                'choice_label' => 'name',
                'label' => 'Kit',
                'placeholder' => 'Choose a kit',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Config::class,
        ]);
    }
}
