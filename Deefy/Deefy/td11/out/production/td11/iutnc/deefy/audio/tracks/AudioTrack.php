<?php

namespace iutnc\deefy\audio\tracks;

class AudioTrack {
    protected string $titre;
    protected string $genre;
    protected int $duree=0;
    protected string $nomFichier;

    public function __construct(string $titre, string $nomFichier) {
        $this->titre = $titre;
        $this->nomFichier = $nomFichier;
    }

    public function __toString(): string {
        return json_encode($this);
    }

    public function __get(string $at): mixed {
        if (!property_exists($this, $at)) {
            throw new \iutnc\deefy\exception\InvalidPropertyNameException("$at: invalide");
        }
        return $this->$at;
    }

    public function setDuree($d=0): void {
        if ($d < 0) {
            throw new \iutnc\deefy\exception\InvalidPropertyValueException("la duree doit etre positive");
        }
        $this->duree = $d;
    }

    public function setGenre($g): void {
        $this->genre = $g;
    }

}


