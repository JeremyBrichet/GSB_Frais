<?php

namespace App\Controller;

use App\Entity\FicheFrais;
use App\Entity\User;
use Container6ljnfR3\getManagerRegistryAwareConnectionProviderService;
use Doctrine\Persistence\ManagerRegistry;
use Http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class InflationController extends AbstractController
{
    #[Route('/inflation', name: 'app_inflation')]
    public function index(ManagerRegistry $registry): \Symfony\Component\HttpFoundation\Response
    {
        $repository = $registry->getRepository(FicheFrais::class);
        $fichesFrais = $repository->findAll();
        $fichesFrais2021 = [];
        foreach ($fichesFrais as $uneFicheFrais){
            if (str_contains($uneFicheFrais->getMois(), '2021')){
                $fichesFrais2021[] = $uneFicheFrais;
            }
        }
        $montantsValidesCumules = 0;
        foreach ($fichesFrais2021 as $uneFicheFrais){
            $montantsValidesCumules = $montantsValidesCumules + $uneFicheFrais->getMontant();
            $montantLigneFFCumules = $montantLigneFFCumules + $uneFicheFrais->getMontant();
            $montantligneFHFCumules = $montantligneFHFCumules + $uneFicheFrais->getMontant();
        }

        $allUsers = $registry->getRepository(User::class)->findAll();
        $nbVisiteurs = count($allUsers);

        return $this->render('inflation/index.html.twig', [
            'primeMontantsValidesCumules' => $montantsValidesCumules * 0.095,
            'primeMontantsValidesCumulesParVisiteur' => $montantsValidesCumules * 0.095 / $nbVisiteurs,
            'primeMontantsLignesFraisCumules' => $montantLigneFFCumules * 0.095,
            'primeMontantsValidesCumulesParVisiteur' => $montantLigneFHFCumules
        ]);
    }
}



