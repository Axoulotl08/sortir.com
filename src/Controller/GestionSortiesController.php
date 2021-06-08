<?php


namespace App\Controller;


use App\Data\SearchData;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Form\LieuType;
use App\Form\SearchType;
use App\Form\SortieType;
use App\Repository\CampusRepository;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
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
    public function nouvelleSorties(
            SortieRepository $sortieRepo,
            EntityManagerInterface $entityManager,
            EtatRepository $etatRepository,
            Request $request
    ) : Response
    {
        $newSortie = new Sortie();
        $sortiForm = $this->createForm(SortieType::class, $newSortie);
        $sortiForm->handleRequest($request);


        $this->bouttonCliqueEtInfoUtilisateur($request, $newSortie, $etatRepository);


        if($sortiForm->isSubmitted() && $sortiForm->isValid()){

            $entityManager->persist($newSortie);
            $entityManager->flush();

            $this->addFlash('success', 'la sortie a bien été ajoutée');
            return $this->redirectToRoute('sortie_liste');
        }

        return $this->render('sorties/nouvelleSorties.html.twig', [
            "sortieForm" => $sortiForm->createView(),
        ]);

    }

    /**
     * @Route("/modifier/{id}", name="modifier")
     */
    public function modifierSortie(
            int $id,
            SortieRepository $sortieRepo,
            EntityManagerInterface $entityManager,
            EtatRepository $etatRepository,
            Request $request

    ) : Response
    {
        $modifySortie = $sortieRepo->find($id);
        if(!$this->isGranted('sortie_edit', $modifySortie));
        {
            $this->addFlash('Erreur', 'Vous n\'avez pas le droit de modifier cette activité');
            return $this->redirectToRoute('sortie_liste');
        }
        $sortiForm = $this->createForm(SortieType::class, $modifySortie);
        $sortiForm->get('ville')->setData($modifySortie->getLieu()->getVille());
        $sortiForm->handleRequest($request);


        $this->bouttonCliqueEtInfoUtilisateur($request, $modifySortie, $etatRepository);

        if($sortiForm->isSubmitted() && $sortiForm->isValid()){

            $entityManager->persist($modifySortie);
            $entityManager->flush();

            $this->addFlash('success', 'la sortie a bien été modifier');
            return $this->redirectToRoute('sortie_liste');
        }

        return $this->render('sorties/modifierSorties.html.twig', [
            "sortieForm" => $sortiForm->createView(),
            'sortie' => $modifySortie
        ]);
    }



    /**
     * @Route("/annuler/{id}", name="annuler")
     */
    public function annulerSortie(
        int $id,
        SortieRepository $sortieRepo,
        EntityManagerInterface $entityManager,
        EtatRepository $etatRepository,
        Request $request
    ) : Response
    {
        $annulerSortie = $sortieRepo->find($id);
        if(!$this->isGranted('sortie_cancelled', $annulerSortie));
        {
            $this->addFlash('Erreur', 'Vous n\'avez pas le droit de supprimer cette activité');
            return $this->redirectToRoute('sortie_liste');
        }
        $sortiForm = $this->createForm(SortieType::class, $annulerSortie);

        $sortiForm->handleRequest($request);

        dump($annulerSortie);
        $annulerSortie->setEtat($etatRepository->find('7'));
        dump($annulerSortie);

        if($sortiForm->isSubmitted() && $sortiForm->isValid()){

            $entityManager->persist($annulerSortie);
            $entityManager->flush();

            $this->addFlash('success', 'la sortie a bien été modifier');
            return $this->redirectToRoute('sortie_liste');
        }

        return $this->render('sorties/annulerSorties.html.twig', [
            "sortieForm" => $sortiForm->createView(),
            'sortie' => $annulerSortie
        ]);
    }

    /**
     * function utilisé par deux des routes
     * @param Request $request
     * @param Sortie $sortieAHydrater
     * @param EtatRepository $etatRepository
     */
    public function bouttonCliqueEtInfoUtilisateur(Request $request, Sortie $sortieAHydrater, EtatRepository $etatRepository): void
    {
        $boutonClique = $request->request->get('validation');
        $createur = $this->getUser()->getParticipant();
        $sortieAHydrater->setOrganisateur($createur);
        $sortieAHydrater->setSiteOrganisateur($createur->getCampus());
        if ($boutonClique == 'enregistrer') {
            $etat = $etatRepository->findOneBy(['id' => '1']);
            $sortieAHydrater->setEtat($etat);
        } elseif ($boutonClique == 'publier') {
            $etat = $etatRepository->findOneBy(['id' => '2']);
            $sortieAHydrater->setEtat($etat);
        }
    }
}
