<?php

namespace App\Controller;

use App\Data\ImportCSV;
use App\Form\ImportCSVType;
use App\Repository\CampusRepository;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/ajoutUtilisateur", name="admin")
     */

    public function ajoutParticipant(Request $request,
                          UserPasswordEncoderInterface $passwordEncoder,
                          UserRepository $userRepository, CampusRepository $campusRepository): Response
    {

        $import = new ImportCSV();
        $form = $this->createForm(ImportCSVType::class, $import);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $participant = $import->importCSV($campusRepository);
        }

        return $this->render('admin/ajoutParticipants.html.twig', [
            'formCSV' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/listeParticipant", name="liste_participant")
     */
    public function listeParticipant(UserRepository $userRepository,
                                     Request $request,
                                     EntityManagerInterface $entityManager)
    {

        $users = $userRepository->findAll();
        if($request->getMethod() == 'POST')
        {
            foreach($users as $user)
            {
                if($request->request->get('inactiv_'.$user->getId()))
                {
                    $user->getParticipant()->setActif(false);
                    $entityManager->persist($user);

                }
            }
            $entityManager->flush();
            foreach($users as $user)
            {
                if($request->request->get('supp_'.$user->getId()))
                {
                    $entityManager->remove($user);
                    //$entityManager->persist($user);

                }
            }
            $entityManager->flush();

            return $this->redirectToRoute('liste_participant');
        }

        return $this->render('admin/listeParticipants.html.twig', [
            'users' => $users
        ]);
    }
}
