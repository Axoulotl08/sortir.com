<?php


namespace App\Controller;


use App\Data\SearchData;
use App\Form\SearchType;
use App\Repository\CampusRepository;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="sortie_")
 */
class GestionSortiesController extends AbstractController
{
    /**
     * @Route("", name="liste")
     */
    public function listeSorties(SortieRepository $sortieRepo, Request $request ): Response
    {
        $data = new SearchData();
        $formFilter = $this->createForm(SearchType::class, $data);
        $formFilter->handleRequest($request);
        $data->particiantid = $this->getUser()->getParticipant()->getId();

        $listeSorties = $sortieRepo->findSearch($data);
        //TODO modifier la requete pour ameliorer



        return $this->render('sorties/listeSorties.html.twig', [
            "listSorties" => $listeSorties,
            "formFilter" => $formFilter->createView(),
        ]);
    }

    /**
     * @Route("/details/{id}", name="details")
     */
    public function detailSorties(int $id, SortieRepository $sortieRepo) : Response
    {
        $sortie = $sortieRepo->find($id);

        return $this->render('sorties/detailsSorties.html.twig', [
            "sortie" => $sortie
        ]);

    }
}
