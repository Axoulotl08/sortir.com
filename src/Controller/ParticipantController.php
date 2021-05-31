<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\User;
use App\Repository\ParticipantRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profil", name="participant")
 */
class ParticipantController extends AbstractController
{
    /**
     * @Route("/", name="_profil")
     */
    public function index(Request $request, EntityManagerInterface $entityManager, ParticipantRepository $repository): Response
    {
        $id = $this->getUser()->getId();
        $user = $repository->find($id);
        dump($user);
        return $this->render('participant/monProfil.htlm.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/{id}", name="_afficher", requirements={"id"="\d+"})
     */
    public function afficherProfil($id): Response
    {

    }
}
