<?php

namespace App\Controller;

use App\Entity\FicheFrais;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FicheDeFraisController extends AbstractController
{
    #[Route('/fiche/de/frais', name: 'app_fiche_de_frais')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $repository = $doctrine->getRepository(FicheFrais::class);
        $fichedefrais = $repository->findBy(['user'=>$user]);
        return $this->render('fiche_de_frais/index.html.twig', [
            'FicheFrais' =>$fichedefrais,
        ]);
    }
}


