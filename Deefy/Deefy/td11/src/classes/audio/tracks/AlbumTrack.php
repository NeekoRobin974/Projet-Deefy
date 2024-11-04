<?php

declare(strict_types=1);

namespace iutnc\deefy\audio\tracks;

class AlbumTrack extends AudioTrack{
    private string $artiste;    //Nom de l'artiste associé à la piste
    private string $album;  //Nom de l'album auquel appartient la piste
    private int $annee; //Année de sortie de la piste
    private int $numeroPiste;   //Numéro de la piste dans l'album

    /**
     * Constructeur
     * @param string $titre
     * @param string $nomFichier
     * @param string $album
     * @param int $numeroPiste
     */
    public function __construct(string $titre, string $nomFichier, string $album, int $numeroPiste = 0){
        parent::__construct($titre, $nomFichier);
        $this->album = $album;
        $this->numeroPiste = $numeroPiste;
    }

    /**
     * Méthode get pour accéder aux propriétés privées
     * @param string $at
     * @return mixed
     * @throws \iutnc\deefy\exception\InvalidPropertyNameException
     */
    public function __get(string $at): mixed{
        if(!property_exists($this, $at)){
            throw new \iutnc\deefy\exception\InvalidPropertyNameException("$at: Invalid property");
        }
        return $this->$at;
    }

    /**
     * Méthode toString pour convertir l'objet en chaîne JSON
     * @return string
     */
    public function __toString(): string{
        return json_encode($this);
    }

    /**
     * Méthode pour définir le nom de l'artiste
     * @param $a
     * @return void
     */
    public function setArtiste($a): void{
        $this->artiste = $a;
    }

    /**
     * Méthode pour définir l'année de sortie de la piste
     * @param $a
     * @return void
     */
    public function setAnnee($a): void{
        $this->annee = $a;
    }
}