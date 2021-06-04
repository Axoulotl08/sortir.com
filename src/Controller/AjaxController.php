<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\LieuType;
use App\Repository\LieuRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ajax", name="ajax_")
 */
class AjaxController extends AbstractController
{
    /**
     * @Route("/donneesDuLieuSelectionne", name="donnéesDuLieuSelectionne")
     */
    public function donnéesDuLieuSelectionne(LieuRepository $lieuRepository, Request $request): Response
    {
        $idLieuSelectionne = $request->get('lieuSelectionne');
        $lieuSelectionne = $lieuRepository->findBy(['id'=>$idLieuSelectionne]);
        $lieuSelectionne = $lieuSelectionne[0];
        $lieuSelectionneJson = $lieuSelectionne->jsonSerialize();
        //$arrData = ['output' => $lieuSelectionne->toString()];
        /*$lieuSelectionne =json_encode($lieuSelectionne);*/
        return  new JsonResponse($lieuSelectionneJson);
    }

    /**
     * @Route("/nouveauLieu", name="nouveauLieu")
     */

    public function nouveauLieu(LieuRepository $lieuRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
       $lieu=new lieu();
       $lieuForm = $this->createForm( LieuType::class, $lieu);
       //$donnees = $request->get('json');
       $lieuForm->handleRequest($request);
       dump($lieuForm);

       if($request->isMethod('POST')){
           $data = json_decode($request->getContent(),true);
           //dd($data);
           $lieuForm->submit($data);
           dd($lieuForm);
       }

       if($lieuForm->isSubmitted()){
           //ne passe pas dans le if

           $entityManager->persist($lieu);
           $entityManager->flush();

           return new JsonResponse([
               'content' => 'ok'
           ]);
       }

       //me renvoi se resultat après soumission du formulaire
        return new JsonResponse([
            'content' => $this->renderView('lieu/nouveauLieuAjax.html.twig', [
                'lieuForm' => $lieuForm->createView()
            ])
        ]);
    }

    /**
     * @Route("/validationNouveauLieu", name="validationNouveauLieu")
     */

    public function validationNouveauLieu(
        LieuRepository $lieuRepository,
        Request $request,
        EntityManagerInterface $entityManager,
        VilleRepository $villeRepository
    ): Response
    {
        $lieu=new lieu();
        $nomLieu = $request->get('lieuNom');
        $lieuRue = $request->get('lieuRue');
        $lieuLat = $request->get('lieuLat');
        $lieuLong = $request->get('lieuLong');
        $lieuVille = $request->get('lieuVille');

        $lieuForm = $this->createForm( LieuType::class, $lieu);
        if($lieuForm->isSubmitted() && $lieuForm->isValid()) {
            dd($lieu);
        }

        $ville = $villeRepository->findOneBy(['id'=> $request->get('lieuVille')]);

        $lieu->setNom($request->get('lieuNom'));
        $lieu->setRue($request->get('lieuRue'));
        $lieu->setLatitude($request->get('lieuLat'));
        $lieu->setLongitude($request->get('lieuLong'));
        $lieu->setVille($ville);
        //dd($lieu);

            $entityManager->persist($lieu);
            $entityManager->flush();

        return new JsonResponse([
            'content' => 'ok'
            ]);
    }


}
