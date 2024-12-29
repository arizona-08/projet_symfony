<?php

namespace App\Form;


use App\Entity\Config;
use App\Entity\Vehicle;
use App\Entity\Kit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('vehicle', EntityType::class, [
            'class' => Vehicle::class,
            'choice_label' => 'model',
            'label' => 'Vehicle',
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

