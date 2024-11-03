<?php

namespace iutnc\deefy\audio\tracks;

class PodcastTrack extends AudioTrack {
    private string $auteur;
    private string $date;

    public function __construct(string $titre, string $nomFichier) {
        parent::__construct($titre, $nomFichier);
    }

    public function __get(string $at):mixed {
        if ( property_exists ($this, $at) ) 
           return $this->$at;
       
       throw new \Exception ("$at: invalide");
    }
}