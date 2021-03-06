<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\AppAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/admin/ajouterParticipant", name="admin_ajouter_participant")
     */
    public function register(Request $request,
                             UserPasswordEncoderInterface $passwordEncoder,
                             GuardAuthenticatorHandler $guardHandler,
                             AppAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            if($form->get('plainPassword')->getData() == $form->get('confirmationPass')->getData())
            {
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
                if($user->getParticipant()->getAdministrateur())
                {
                    $user->setRoles(["ROLE_ADMIN"]);
                }
                else
                {
                    $user->setRoles(["ROLE_USER"]);
                }
                $user->setUsername(strtolower($user->getParticipant()->getNom()) .'-'. strtolower($user->getParticipant()->getPrenom()));
                $user->getParticipant()->setActif(true);
                $user->getParticipant()->setImageName('photo_defaut.jpg');
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                return $guardHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $authenticator,
                    'main' // firewall name in security.yaml
                );
            }
            else
            {
                $this->addFlash('Erreur', 'Mot de passe et confirmation diff??rent');
                return $this->render('registration/register.html.twig', [
                    'registrationForm' => $form->createView(),
                ]);
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
