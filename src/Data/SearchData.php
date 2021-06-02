<?php


namespace App\Data;


use App\Entity\Campus;
use App\Entity\Participant;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class SearchData
{

    /**
     * @var string
     */
    public $titleSearch;


    /**
     * @var integer
     */
    public $particiantid;

    /**
     * @var Campus
     */
    public $campus;

    /**
     * @var null|DateType
     */
    public $dateIntervalDebut;

    /**
     * @var null|DateType
     */
    public $dateIntervalFin;

    /**
     * @var boolean
     */
    public $organisateur = true;

    /**
     * @var boolean
     */
    public $estInscrit = true;

    /**
     * @var boolean
     */
    public $nEstPasInscrit = true;

    /**
     * @var boolean
     */
    public $passee= false;

}
