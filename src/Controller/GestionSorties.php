<?php


namespace App\Controller;


use App\Repository\CampusRepository;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="sortie_")
 */
class GestionSorties extends AbstractController
{
    /**
     * @Route("", name="liste")
     */
    public function listeSorties(SortieRepository $sortieRepo, CampusRepository $campusRepo): Response
    {
        $listeSorties = $sortieRepo->findAll();

        $listeCampus = $campusRepo->findAll();

        return $this->render('sorties/listeSorties.html.twig', [
            "listSorties" => $listeSorties,
            "listeCampus" => $listeCampus
        ]);
    }
}
