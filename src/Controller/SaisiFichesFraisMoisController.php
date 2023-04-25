<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\FicheFrais;
use App\Entity\FraisForfait;
use App\Entity\LigneFraisForfait;
use App\Entity\LigneFraisHorsForfait;
use App\Form\NewFicheFraisLignesFraisForfaitType;
use App\Form\NewFicheFraisLignesFraisHorsForfaitType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SaisiFichesFraisMoisController extends AbstractController
{
    #[Route('/saisi/fiches/frais/mois', name: 'app_saisi_fiches_frais_mois')]
    public function index(ManagerRegistry $doctrine, Request $request ): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $users = $this->getUser();
        $date = new \DateTime();
        $mois = $date->format('Ym');
        $repository = $doctrine->getRepository(FicheFrais::class);
        $ficheFrais = $repository->findOneBy(array('user' => $users, 'mois' => $mois));

        if($ficheFrais == null){
            $entityManager = $doctrine->getManager();
            $ficheFrais = new FicheFrais();
            $ficheFrais -> setUser($users);
            $ficheFrais -> setMois($mois);
            $ficheFrais -> setNbJustificatifs(0);
            $ficheFrais -> setMontantValide(0);
            $ficheFrais -> setDateModif(new \DateTime());
            $ficheFrais -> setEtat($doctrine->getManager()->getRepository(FraisForfait::class));

            $lffNuite = new LigneFraisForfait();
            $lffNuite ->setQuantite(0);
            $lffNuite ->setFicheFrais($ficheFrais);
            $lffNuite ->setFraisForfait($doctrine->getRepository(FraisForfait::class)->find(3));
            $ficheFrais->addLignefraisforfait($lffNuite);

            $lffForfaitEtape = new LigneFraisForfait();
            $lffForfaitEtape ->setQuantite(0);
            $lffForfaitEtape ->setFicheFrais($ficheFrais);
            $lffForfaitEtape ->setFraisForfait($doctrine->getRepository(FraisForfait::class)->find(1));
            $ficheFrais->addLignefraisforfait($lffForfaitEtape);

            $lffForfaitkilometrique = new LigneFraisForfait();
            $lffForfaitkilometrique ->setQuantite(0);
            $lffForfaitkilometrique ->setFicheFrais($ficheFrais);
            $lffForfaitkilometrique ->setFraisForfait($doctrine->getRepository(FraisForfait::class)->find(2));
            $ficheFrais->addLignefraisforfait($lffForfaitkilometrique);

            $lffRepas = new LigneFraisForfait();
            $lffRepas ->setQuantite(0);
            $lffRepas ->setFicheFrais($ficheFrais);
            $lffRepas ->setFraisForfait($doctrine->getRepository(FraisForfait::class)->find(4));
            $ficheFrais->addLignefraisforfait($lffRepas);

            $repository = $doctrine->getRepository(Etat::class);
            $etat = $repository->find(2);
            $ficheFrais->setEtat($etat);

            $entityManager->persist($ficheFrais);
            $entityManager->flush();
        }
        $formLignesFraisforfait = $this->createForm(NewFicheFraisLignesFraisForfaitType::class, null, ['currentFicheFrais' => $ficheFrais]);
        $formLignesFraisforfait->handleRequest($request);
        if($formLignesFraisforfait->isSubmitted() && $formLignesFraisforfait->isValid()){
            $ficheFrais->getLignefraisforfait()[0]->setQuantite($formLignesFraisforfait->get('Forfait Etape')->getData());
            $ficheFrais->getLignefraisforfait()[1]->setQuantite($formLignesFraisforfait->get('Nuite')->getData());
            $ficheFrais->getLignefraisforfait()[2]->setQuantite($formLignesFraisforfait->get('Forfait Kilometrique')->getData());
            $ficheFrais->getLignefraisforfait()[3]->setQuantite($formLignesFraisforfait->get('Repas')->getData());

            $doctrine->getManager()->persist($ficheFrais);
            $doctrine->getManager()->flush();
        }

        $newLignefraisHorsForfait = new LigneFraisHorsForfait($ficheFrais, null, null, null);
        $formLignesFraisHorsforfait = $this->createForm(NewFicheFraisLignesFraisHorsForfaitType::class, $newLignefraisHorsForfait);
        $formLignesFraisHorsforfait->handleRequest($request);
        if ($formLignesFraisHorsforfait->isSubmitted() && $formLignesFraisHorsforfait->isValid()){
            $newLignefraisHorsForfait->setFicheFrais($ficheFrais);
            $ficheFrais->addLigneFraisHorsForfait($newLignefraisHorsForfait);
            $doctrine->getManager()->persist($newLignefraisHorsForfait);
            $doctrine->getManager()->persist($ficheFrais);
            $doctrine->getManager()->flush();
        }


        return $this->render('saisi_fiches_frais_mois/index.html.twig', [
            'controller_name' => 'SaisiFichesFraisMoisController',
            'ficheFrais' => $ficheFrais,
            'formLFF' => $formLignesFraisforfait->createView(),
            'formLFHF' => $formLignesFraisHorsforfait->createView(),
        ]);
    }
}
