<?php


namespace App\Data;


use App\Entity\Campus;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\Date;

class SearchData
{

    /**
     * @var string
     */
    public $titleSearch;

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
