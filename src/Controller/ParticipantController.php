<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\User;
use App\Form\ModificationProfilType;
use App\Form\ParticipantType;
use App\Form\RegistrationFormType;
use App\Repository\ParticipantRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/profil", name="participant")
 */
class ParticipantController extends AbstractController
{
    /**
     * @Route("/", name="_profil")
     */
    public function modifierProfil(Request $request,
                                   EntityManagerInterface $entityManager,
                                   UserPasswordEncoderInterface $passwordEncoder,
                                   UserRepository $repository): Response
    {
        $id = $this->getUser();
        if (!$id) {
            return $this->redirectToRoute('app_login');
        }
        $user = $repository->find($id->getId());

        dump($user);
        //$participant = $user->getParticipant();
        $userForm = $this->createForm(ModificationProfilType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            if ($userForm->get('plainPassword')->getData() != null && $userForm->get('confirmationPass')->getData() != null) {
                if ($userForm->get('plainPassword')->getData() == $userForm->get('confirmationPass')->getData()) {
                    $user->setPassword(
                        $passwordEncoder->encodePassword(
                            $user,
                            $userForm->get('plainPassword')->getData()
                        )
                    );
                }
                else {
                    $this->addFlash('Erreur', 'Mot de passe et confirmation différent');
                    return $this->render('participant/monProfil.htlm.twig', [
                        'formParticipant' => $userForm->createView()
                    ]);
                }
            }
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('sortie_liste');
        }
        return $this->render('participant/monProfil.htlm.twig', [
            'userForm' => $userForm->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="_afficher", requirements={"id"="\d+"})
     */
    public function afficherProfil($id,
                                   UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);
        return $this->render('participant/profil.html.twig', [
            'user' => $user
        ]);
    }
}
