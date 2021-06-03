<?php


namespace App\Data;


use App\Entity\Participant;
use App\Repository\CampusRepository;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 *
 * @Vich\Uploadable()
 */
class ImportCSV
{
    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="csv", fileNameProperty="imageName")
     *
     * @var null|File
     */
    private $imageFile;

    /**
     *
     * @var null|string
     */
    private $imageName;

    /**
     *
     * @var null|\DateTimeInterface
     */
    private $updatedAt;

    //private $campusRepository;

    public function __construct()
    {
    }

    public function importCSV(CampusRepository $campusRepository): Participant
    {
        $lecture = fopen('fichierCSV/'.$this->imageName, 'r');
        $listCampus = $campusRepository->findAll();
        //dd($element);
        while(!feof($lecture))
        {
            $ligne = fgets($lecture);
            $element = explode(';', $ligne);
            $participant = new Participant();
            $participant->setNom($element[0]);
            $participant->setPrenom($element[1]);
            $participant->setTelephone($element[2]);
            $participant->setMail($element[3]);
            foreach($listCampus as $campus)
            {
                if($campus->getNom() == $element[4])
                {
                    $participant->setCampus($campus);
                }
            }
            if(substr($element[5], 0, 5) == true){
                $participant->setAdministrateur(true);
            }
            else
            {
                $participant->setAdministrateur(false);
            }
            $participants[] = $participant;
        }
        dd($participants);
        return $participants;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

}