<?php

namespace App\Form;

use App\Entity\LigneFraisHorsForfait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewFicheFraisLignesFraisHorsForfaitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date',
            ])

            ->add('libelle', DateType::class, [
                'label' => 'libelle',
            ])

            ->add('montant', DateType::class, [
                'widget' => 'Montant',
                'currency' => 'EUR',
            ])

            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date',
            ])

            ->add('Valider', DateType::class, [
                'label' => 'Ajouter',
                'attr' => ['class' => 'btn btn-warning'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => LigneFraisHorsForfait::class,
        ]);
    }
}
