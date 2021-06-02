<?php

namespace App\Controller;

use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Sortie;

class InscriptionSortieController extends AbstractController
{
    /**
     * @Route("/inscription/{idSortie}/{idParticipant}", name="inscription_sortie",
     *     requirements={"idSortie"="\d+", "idParticipant"="\d+"})
     */
    public function inscription(int $idSortie, int $idParticipant,
                          SortieRepository $sortieRepository,
                          ParticipantRepository $participantRepository,
                          EntityManagerInterface $entityManager): Response
    {

        $sortie = $sortieRepository->find($idSortie);
        $participant = $participantRepository->find($idParticipant);
        //Vérification du nombre d'inscrit
        $nbInscritMax = $sortie->getNbInscriptionsMax();
        $nbInscrit = $sortie->getInscrits()->count();
        //Si encore de la place, on ajoute le participant dans la sortie
        if($nbInscrit < $nbInscritMax) {
            $sortie->addInscrit($participant);
            $entityManager->persist($sortie);
            $entityManager->flush();
        }
        return $this->redirectToRoute('sortie_liste');
    }

    /**
     * @Route("/desinscription/{idSortie}/{idParticipant}", name="desinscription_sortie",
     *     requirements={"idSortie"="\d+", "idParticipant"="\d+"})
     */
    public function desinscription(int $idSortie, int $idParticipant,
                                   SortieRepository $sortieRepository,
                                   ParticipantRepository $participantRepository,
                                   EntityManagerInterface $entityManager)
    {
        $sortie = $sortieRepository->find($idSortie);
        $participant = $participantRepository->find($idParticipant);
        $sortie->removeInscrit($participant);
        $entityManager->persist($sortie);
        $entityManager->flush();
        return $this->redirectToRoute('sortie_liste');
    }
}
