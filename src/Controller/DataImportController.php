<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\FicheFrais;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class DataImportController extends AbstractController
{
    #[Route('/importuser', name: 'app_import_users')]
    public function index(ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {
        $usersjson = file_get_contents("visiteur.json");
        $users = json_decode($usersjson);

        foreach($users as $user){
            var_dump($user->id);
            $newUser = new User();
            $newUser->setLogin($user->login);
            $newUser->setNom($user->nom);
            $newUser->setPrenom($user->prenom);
            $newUser->setCp($user->cp);
            $newUser->setVille($user->ville);
            $newUser->setDateEmbauche(new \DateTime($user->dateEmbauche));
            $newUser->setAdresse($user->adresse);
            $newUser->setOldId($user->id);
            $plaintextpassword = $user->mdp;
            $hashedpassword = $passwordHasher->hashPassword($newUser, $plaintextpassword);

            $newUser->setPassword($hashedpassword);
            $doctrine->getManager()->persist($newUser);
            $doctrine->getManager()->flush();
        }
        return $this->render('data_import/index.html.twig', [
            'controller_name' => 'DataImportController',
        ]);
    }

    #[Route('/importfiches', name: 'app_import_fiches')]
    public function importFichesFrais(ManagerRegistry $doctrine): Response
    {
        $fichesfraisjson = file_get_contents("fichesfrais.json");
        $fichesfrais = json_decode($fichesfraisjson);


        foreach($fichesfrais as $fichefrais)
        {
            $newFicheFrais = new FicheFrais();
            $newFicheFrais->setMois($fichefrais->mois);
            $newFicheFrais->setMontant($fichefrais->montantValide);
            $newFicheFrais->setNbJustificatif($fichefrais->nbJustificatifs);
            $newFicheFrais->setDateModification(new \DateTime($fichefrais->dateModif));
            $user = $doctrine->getRepository(User::class)->findOneBy(['oldId' => $fichefrais->idVisiteur]);
            $newFicheFrais->setUser($user);

            switch ($fichefrais->idEtat){
                case "VA":
                    $etat = $doctrine->getRepository(Etat::class)->find(4);
                    break;

                case "CR":
                    $etat = $doctrine->getRepository(Etat::class)->find(2);
                    break;

                case "CL":
                    $etat = $doctrine->getRepository(Etat::class)->find(1);
                    break;

                case "RB":
                    $etat = $doctrine->getRepository(Etat::class)->find(3);
                    break;

            }

            $newFicheFrais->setEtat($etat);
            var_dump($newFicheFrais->getMois());
            var_dump($newFicheFrais->getUser()->getoldId());

            //$doctrine->getManager()->persist($newFicheFrais);
            //$doctrine->getManager()->flush();
        }
        return $this->render('data_import/index.html.twig', [
            'controller_name' => 'DataImportController',
        ]);
    }
}
