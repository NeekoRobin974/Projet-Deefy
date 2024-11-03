<?php


// class AlbumTrack {
//     public string $titre;
//     public string $artiste;
//     public string $album;
//     public int $annee;
//     public int $numeroPiste;
//     public string $genre;
//     public int $duree;
//     public string $nomFichier;

//     public function __construct(string $titre, string $nomFichier, string $album, int $numeroPiste) {
//         $this->titre = $titre;
//         $this->nomFichier = $nomFichier;
//         $this->album = $album;
//         $this->numeroPiste = $numeroPiste;
//     }

//     public function __toString(): string {
//         return json_encode($this);
//     }
// }

namespace iutnc\deefy\audio\tracks;

class AlbumTrack extends AudioTrack {
    private string $artiste;
    private string $album;
    private int $annee;
    private int $numeroPiste;

    public function __construct(string $titre, string $nomFichier, string $album, int $numeroPiste = 0) {
        parent::__construct($titre, $nomFichier);
        $this->album = $album;
        $this->numeroPiste = $numeroPiste;
    }

    public function __get(string $at): mixed {
        if (!property_exists($this, $at)) {
            throw new \iutnc\deefy\exception\InvalidPropertyNameException("$at: invalid property");
        }
        return $this->$at;
    }

    public function __toString(): string {
        return json_encode($this);
    }

    public function setArtiste($a): void {
        $this->artiste = $a;
    }

    public function setAnnee($a): void {
        $this->annee = $a;
    }
}
