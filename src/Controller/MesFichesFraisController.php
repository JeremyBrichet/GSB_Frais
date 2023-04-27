<?php

namespace App\Controller;

use App\Entity\FicheFrais;
use App\Form\MesFichesFraisType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\Cloner\Data;

class MesFichesFraisController extends AbstractController
{
    #[Route('/mes/fiches/frais', name: 'app_mes_fiches_frais')]
    public function index(ManagerRegistry $doctrine, Request $request ): Response
    {
        $selectedficheFrais = null;
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $repository = $doctrine->getRepository(FicheFrais::class);
        $fichesFrais = $repository->findBy(array('user' => $user));
        $listeMois = [];
        foreach ($fichesFrais as $fF){
            $listeMois[$fF->getMois()] = $fF->getMois();
        }
        $formff = $this->createForm(MesFichesFraisType::class, null , [
            'listeMois' => $listeMois
            ]);
        $formff->handleRequest($request);
        if ($formff->isSubmitted() && $formff->isValid()) {
            $selectedficheFrais = $repository->findOneBy(['user' => $user,
                'mois' => $formff->get('liste_mois')->getData()]);
        }
        return $this->render('mes_fiches_frais/index.html.twig', [
            'controller_name' => 'MesFichesFraisController',
            'myForm' => $formff->createView(),
            'selectFicheFrais' => $selectedficheFrais,
        ]);
    }
}
