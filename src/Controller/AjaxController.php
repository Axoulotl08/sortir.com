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
     * permet de recuperer les informations concernant le lieux selectionnée dans les formulaire de création et de modification d'une sortie
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

    public function nouveauLieu(LieuRepository $lieuRepository, Request $request, EntityManagerInterface $entityManager, VilleRepository $villeRepository): Response
    {
       $lieu=new lieu();
       $lieuForm = $this->createForm( LieuType::class, $lieu);
       $lieuForm->handleRequest($request);

       if($request->isMethod('POST')){
           $data = json_decode($request->getContent(), true);
           $data2 =[];
           foreach ($data as $index=>$value){
               if(strpos($index,'lieu[')===0){
                   $index=substr($index,5,-1);
                   $data2[$index] = $value;
               }
           }
           $lieuForm->submit($data2);
       }

       if($lieuForm->isSubmitted()){

           $entityManager->persist($lieu);
           $entityManager->flush();

           $tableauListeLieu =[];
           $listeLieu = $lieuRepository->findAll();
           $i=0;
           forEach ($listeLieu as $lieuDansListe){
              $tableauListeLieu[$i] = $lieuDansListe->jsonSerialize();
               $i++;

           };
           return new JsonResponse($tableauListeLieu);
       }

       //me renvoi se resultat après soumission du formulaire
        return new JsonResponse([
            'content' => $this->renderView('lieu/nouveauLieuAjax.html.twig', [
                'lieuForm' => $lieuForm->createView()
            ])
        ]);
    }

    /**
     * @Route("/lieuDependantDeVille", name="lieuDependantDeVille")
     */
    public function misAJoursLieuEnFonctionDesVille(EntityManagerInterface $entityManager, LieuRepository $lieuRepository, Request $request){

        $idVille = $request->query->get("idVille");
        $lieux = $lieuRepository->lieuSelonVille($idVille);

        $tableauVille = [];
        foreach($lieux as $lieu){
            $tableauVille[] = [
                "id"=>$lieu->getId(),
                "nom"=>$lieu->getNom()
            ];
        }

        return new JsonResponse($tableauVille);
    }

}
