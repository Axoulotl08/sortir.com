<?php

namespace App\Repository;

use App\Entity\Sortie;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findSearch(\App\Data\SearchData $data)
    {


        $query = $this
            ->createQueryBuilder('sorties')
            ->leftJoin('sorties.organisateur', 'organisateur')
            ->leftJoin('sorties.etat', 'etat')
            ->leftJoin('sorties.inscrits', 'participants')
            ->leftJoin('sorties.siteOrganisateur', 'campus')
            ->select('sorties','organisateur','etat','participants');


        if(!empty($data->organisateur)){

            $query = $query
                ->orWhere('organisateur.id = :oId')
                ->setParameter('oId', $data->particiantid);
        }

        if(!empty($data->estInscrit)){
            $query = $query
                ->orWhere('participants.id = :pId')
                ->setParameter('pId', $data->particiantid);
        }

        if(!empty($data->nEstPasInscrit)) {

            $query = $query
                ->orWhere(':pId NOT MEMBER OF sorties.inscrits')
                ->setParameter('pId', $data->particiantid);
        }


        if(!empty($data->passee)){
            $query = $query
                ->orWhere('sorties.dateHeureDebut <= :sDateDuJours')
                ->setParameter('sDateDuJours', new \DateTime());
        }

        if(!empty($data->campus)){
            $query = $query
                ->andWhere('campus.id = :cId' )
                ->setParameter('cId', $data->campus->getId());
        }

        if(!empty($data->titleSearch)){
            $query = $query
                ->andWhere('sorties.nom Like :sName')
                ->setParameter('sName', "%{$data->titleSearch}%" );
        }

        if(!empty($data->dateIntervalDebut)){
            $query = $query
                ->andWhere('sorties.dateHeureDebut >= :sDateDebut')
                ->setParameter('sDateDebut', $data->dateIntervalDebut);
        }

        if(!empty($data->dateIntervalFin)){
            $query = $query
                ->andWhere('sorties.dateHeureDebut <= :sDateFin')
                ->setParameter('sDateFin', $data->dateIntervalFin);
        }


        $query = $query->andWhere('etat.id != :eID')
                ->setParameter('eID',7);

        return $query->getQuery()->getResult();
    }
}
