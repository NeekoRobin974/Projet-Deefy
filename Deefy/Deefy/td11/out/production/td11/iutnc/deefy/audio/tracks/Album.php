<?php

namespace iutnc\deefy\audio\tracks;

class Album extends \iutnc\deefy\audio\lists\AudioList {
    private string $artiste;
    private string $dateSortie;

    public function __construct(string $nom, array $pistes) {
        if (empty($pistes)) {
            throw new \Exception("il faut au moins un track");
        }
        parent::__construct($nom, $pistes);
    }

    public function setArtiste(string $artiste): void {
        $this->artiste = $artiste;
    }

    public function setDateSortie(string $dateSortie): void {
        $this->dateSortie = $dateSortie;
    }

    public function __toString(): string {
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