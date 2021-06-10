<?php


namespace App\Data;


class SearchVilleCampus
{
    /**
     * @var string
     */
    public $nomSearch;

    /**
     * @return string
     */
    public function getNomSearch(): string
    {
        return $this->nomSearch;
    }

    /**
     * @param string $nomSearch
     */
    public function setNomSearch(string $nomSearch): void
    {
        $this->nomSearch = $nomSearch;
    }



}