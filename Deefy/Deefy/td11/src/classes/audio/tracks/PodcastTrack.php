<?php

declare(strict_types=1);

namespace iutnc\deefy\audio\tracks;

class PodcastTrack extends AudioTrack{
    private string $auteur; //Auteur du podcast
    private string $date;   //Date de sortie du podcast

    /**
     * Constructeur
     * @param string $titre
     * @param string $nomFichier
     */
    public function __construct(string $titre, string $nomFichier){
        parent::__construct($titre, $nomFichier);
    }

    /**
     * Méthode get pour accéder aux propriétés privées
     * @param string $at
     * @return mixed
     * @throws \Exception
     */
    public function __get(string $at):mixed{
        if( property_exists ($this, $at) )
           return $this->$at;
       throw new \Exception ("$at: Invalide");
    }
}