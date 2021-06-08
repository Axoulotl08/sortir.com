<?php

namespace App\Controller;

use App\Data\ImportCSV;
use App\Entity\User;
use App\Form\ImportCSVType;
use App\Repository\CampusRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/ajoutUtilisateur", name="admin")
     */
    public function ajoutParticipant(Request $request,
                        UserPasswordEncoderInterface $passwordEncoder,
                        EntityManagerInterface $entityManager,
                        CampusRepository $campusRepository,
                        SluggerInterface $slugger): Response
    {

        $import = new ImportCSV();
        $form = $this->createForm(ImportCSVType::class, $import);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $csvFile = $form->get('csvFileName')->getData();

            if($csvFile)
            {
                $originalFilename = pathinfo($csvFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.csv';
            }
            try {
                $csvFile->move(
                    $this->getParameter('csv_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            $import->setCsvFileName($newFilename);
            $participants = $import->importCSV($campusRepository);
            dump($participants);
            foreach($participants as $participant)
            {
                $user = new User();
                $user->setParticipant($participant);
                $user->setUsername(strtolower($user->getParticipant()->getNom()) .'-'. strtolower($user->getParticipant()->getPrenom()));
                $user->getParticipant()->setActif(true);
                $user->getParticipant()->setImageName('photo_defaut.jpg');
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        'Bonjour1'
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
                $entityManager->persist($user);
            }
            $entityManager->flush();

            return $this->redirectToRoute('liste_participant');
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
