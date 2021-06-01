<?php


namespace App\Controller;


use App\Data\SearchData;
use App\Form\SearchType;
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
        $data = new SearchData();
        $formFilter = $this->createForm(SearchType::class, $data);
        $listeSorties = $sortieRepo->findAll();

        $listeCampus = $campusRepo->findAll();

        return $this->render('sorties/listeSorties.html.twig', [
            "listSorties" => $listeSorties,
            "listeCampus" => $listeCampus,
            "formFilter" => $formFilter->createView()
        ]);
    }
}
