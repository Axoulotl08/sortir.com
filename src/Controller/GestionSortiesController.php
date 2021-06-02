<?php


namespace App\Controller;


use App\Data\SearchData;
use App\Entity\Sortie;
use App\Form\SearchType;
use App\Form\SortieType;
use App\Repository\CampusRepository;
use App\Repository\EtatRepository;
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
        if ($data->campus == null){
            $data->campus = $this->getUser()->getParticipant()->getCampus();
        }

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

    /**
     * @Route("/nouvelleSortie", name="new")
     */
    public function nouvelleSorties(SortieRepository $sortieRepo, EtatRepository $etatRepository, Request $request) : Response
    {
        $newSortie = new Sortie();
        $sortiForm = $this->createForm(SortieType::class, $newSortie);
        $boutonClique = $request->request->get('validation');
        $sortiForm->handleRequest($request);
        if($boutonClique == 'enregistrer'){
            $etat = $etatRepository->findOneBy(['id'=>'1']);
            $newSortie->setEtat($etat);
        }
        dump($newSortie);

        return $this->render('sorties/nouvelleSorties.html.twig', [
            "sortieForm" => $sortiForm->createView(),
        ]);

    }
}
