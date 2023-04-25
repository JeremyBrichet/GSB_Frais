<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewFicheFraisLignesFraisForfaitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $ficheFrais = $options['currentFicheFrais'];
        if ($ficheFrais->getLigneFraisForfait()[0] != null){
            $qteForfaitEtape = $ficheFrais->getLigneFraisforfait()[0]->getQuantite();
        } else { $qteForfaitEtape = 0; }

        if ($ficheFrais->getLigneFraisForfait()[1] != null){
            $qteNuite = $ficheFrais->getLigneFraisforfait()[1]->getQuantite();
        } else { $qteNuite = 0; }

        if ($ficheFrais->getLigneFraisForfait()[2] != null){
            $qteForfaitKilometrique = $ficheFrais->getLigneFraisforfait()[2]->getQuantite();
        } else { $qteForfaitKilometrique = 0; }

        if ($ficheFrais->getLigneFraisForfait()[3] != null){
            $qteRepas = $ficheFrais->getLigneFraisforfait()[3]->getQuantite();
        } else { $qteRepas = 0; }

        $builder
            ->add('Forfait Etape', NumberType::class,[
            'label' => 'Forfait Etape',
            'data' => $qteForfaitEtape,
                'attr' => [
                    'placeholder' => 'Forfait Etape',
                ],
        'row_attr' => [
            'class' => 'form-floating',
        ],
        ])
            ->add('Nuite', NumberType::class,[
                'label' => 'Nuite',
                'data' => $qteNuite,
                'attr' => [
                    'placeholder' => 'Nuite',
                ],
                'row_attr' => [
                    'class' => 'form-floating',
                ],
            ])
            ->add('Forfait Kilometrique', NumberType::class,[
                'label' => 'Forfait Kilometrique',
                'data' => $qteForfaitKilometrique,
                'attr' => [
                    'placeholder' => 'Forfait Kilometrique',
                ],
                'row_attr' => [
                    'class' => 'form-floating',
                ],
            ])
            ->add('Repas', NumberType::class,[
                'label' => 'Repas',
                'data' => $qteRepas,
                'attr' => [
                    'placeholder' => 'Repas',
                ],
                'row_attr' => [
                    'class' => 'form-floating',
                ],
            ])
            ->add('Valider', SubmitType::class,[
                'label' => 'Mettre à jour les frais forfaitisés',
                'attr' => ['class' => 'btn btn-success'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'currentFicheFrais' => null,
        ]);
    }
}
