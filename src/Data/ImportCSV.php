<?php


namespace App\Data;


use App\Entity\Participant;
use App\Repository\CampusRepository;
use Symfony\Component\HttpFoundation\File\File;



/**
 *
 */
class ImportCSV
{

    private $csvFileName;

    public function __construct()
    {
    }

    public function importCSV(CampusRepository $campusRepository): array
    {
        $lecture = fopen('fichierCSV/'.$this->csvFileName, 'r');
        $listCampus = $campusRepository->findAll();
        dump($listCampus);
        while(!feof($lecture))
        {
            $ligne = fgets($lecture);
            dump($ligne);
            $separation = explode(';', $ligne);
            $participant = new Participant();
            $participant->setNom($separation[0]);
            if($ligne != false)
            {
                if(isset($separation[1])){

                    $participant->setPrenom($separation[1]);
                }
                if(isset($separation[1])){
                    $participant->setTelephone($separation[2]);
                }
                if(isset($separation[3]))
                {
                    $participant->setMail($separation[3]);
                }
                if(isset($separation[4]))
                {
                    foreach($listCampus as $campus)
                    {
                        if($campus->getNom() == strtoupper($separation[4]))
                        {
                            $participant->setCampus($campus);
                        }
                    }
                }
                if(isset($separation[5]))
                {
                    if(substr($separation[5], 0, 3) == "oui"){
                        $participant->setAdministrateur(true);
                    }
                    else
                    {
                        $participant->setAdministrateur(false);
                    }
                }
                $participants[] = $participant;
            }
        }
        return $participants;
    }


    public function getCsvFileName()
    {
        return $this->csvFileName;
    }

    public function setCsvFileName($csvFileName): self
    {
        $this->csvFileName = $csvFileName;
        return $this;
    }

}