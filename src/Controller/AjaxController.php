<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Repository\LieuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function MongoDB\BSON\toJSON;

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
}
