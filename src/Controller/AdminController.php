<?php

namespace App\Controller;

use App\Data\ImportCSV;
use App\Form\ImportCSVType;
use App\Repository\CampusRepository;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
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

    public function index(Request $request,
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
    public function listeParticipant(UserRepository $userRepository)
    {
        $user = $userRepository->findAll();
        if(!user)
        {
            //gérer l'erreur
        }


        $this->render('admin/listeParticipants.html.twig', [
            'user' => $user
        ]);
    }
}
