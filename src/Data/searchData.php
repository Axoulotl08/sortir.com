<?php


namespace App\Data;


use App\Entity\Campus;
use Symfony\Component\Validator\Constraints\Date;

class searchData
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
     * @var null|Date
     */
    public $dateIntervalDebut;

    /**
     * @var null|Date
     */
    public $dateIntervalFin;

    /**
     * @var boolean
     */
    public $organisateur;

    /**
     * @var boolean
     */
    public $estInscrit;

    /**
     * @var boolean
     */
    public $nEstPasInscrit;

    /**
     * @var boolean
     */
    public $passé;

}
