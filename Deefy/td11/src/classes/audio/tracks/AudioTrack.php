<?php

declare(strict_types=1);

namespace iutnc\deefy\audio\tracks;

class AudioTrack {
    protected string $titre;    //Titre de la piste
    protected string $genre;    //Genre de la piste
    protected int $duree;   //Durée de la piste
    protected string $nomFichier;   //Nom du fichier

    /**
     * Constructeur
     * @param string $titre
     * @param string $nomFichier
     * @param int $duree
     */
    public function __construct(string $titre, string $nomFichier, int $duree = 0) {
        $this->titre = $titre;
        $this->nomFichier = $nomFichier;
        $this->duree = $duree;
    }

    /**
     * Méthode toString pour convertir l'objet en chaîne JSON
     * @return string
     */
    public function __toString(): string {
        return json_encode($this);
    }

    /**
     * Méthode get pour accéder aux propriétés protégées
     * @param string $at
     * @return mixed
     * @throws \iutnc\deefy\exception\InvalidPropertyNameException
     */
    public function __get(string $at): mixed {
        if (!property_exists($this, $at)) {
            throw new \iutnc\deefy\exception\InvalidPropertyNameException("$at: Invalide");
        }
        return $this->$at;
    }

    /**
     * Méthode pour définir la durée de la piste
     * @param int $d
     * @return void
     * @throws \iutnc\deefy\exception\InvalidPropertyValueException
     */
    public function setDuree(int $d): void {
        if ($d < 0) {
            throw new \iutnc\deefy\exception\InvalidPropertyValueException("La durée doit être positive");
        }
        $this->duree = $d;
    }

    /**
     * Méthode pour définir la genre de la piste
     * @param string $g
     * @return void
     */
    public function setGenre(string $g): void {
        $this->genre = $g;
    }
}
