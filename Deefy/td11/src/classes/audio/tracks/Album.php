<?php

declare(strict_types=1);

namespace iutnc\deefy\audio\tracks;

class Album extends \iutnc\deefy\audio\lists\AudioList{
    private string $artiste;
    private string $dateSortie;

    /**
     * Constructeur
     * @param string $nom
     * @param array $pistes
     * @throws \Exception
     */
    public function __construct(string $nom, array $pistes){
        if(empty($pistes)){
            throw new \Exception("Il faut au moins un track");
        }
        parent::__construct($nom, $pistes);
    }

    /**
     * Méthode pour définir le nom de l'artiste
     * @param string $artiste
     * @return void
     */
    public function setArtiste(string $artiste): void{
        $this->artiste = $artiste;
    }

    /**
     * Méthode pour définir la date de sortie
     * @param string $dateSortie
     * @return void
     */
    public function setDateSortie(string $dateSortie): void{
        $this->dateSortie = $dateSortie;
    }

    /**
     * Méthode toString pour convertir l'objet en chaîne JSON
     * @return string
     */
    public function __toString(): string{
        return json_encode([
            'nom' => $this->nom,
            'artiste' => $this->artiste,
            'dateSortie' => $this->dateSortie,
            'nbPistes' => $this->nbPistes,
            'dureeTotale' => $this->dureeTotale,
            'pistes' => $this->pistes,
        ]);
    }
}