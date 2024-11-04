<?php

namespace iutnc\deefy\render;

use \iutnc\deefy\audio\lists\AudioList;

class AudioListRenderer implements Renderer {
    private AudioList $audioList;

    public function __construct(AudioList $audioList) {
        $this->audioList = $audioList;
    }

    public function render(int $selector = Renderer::COMPACT): string {
        //ignore le selecteur 
        $html = "<h2>" . htmlspecialchars($this->audioList->nom) . "</h2>\n";
        $html .= "<ul>\n";

        foreach ($this->audioList->pistes as $piste) {
            $html .= "<li>" . htmlspecialchars($piste->titre) . " (" . $piste->duree . " secondes ) </li>\n";

            // Lien pour écouter la piste audio (fichier sauvegardé dans le répertoire /audio)
            $html .= "<audio controls>\n";
            $html .= "<source src='./audio/" . htmlspecialchars($piste->nomFichier) . "' type='audio/mpeg'>\n";
            $html .= "Votre navigateur ne supporte pas l'élément audio.\n";
            $html .= "</audio>\n";
        }

        $html .= "</ul>\n";
        $html .= "<p><strong>Nombre de pistes :</strong> " . $this->audioList->nbPistes . "</p>\n";
        $html .= "<p><strong>Durée totale :</strong> " . $this->audioList->dureeTotale . " secondes</p>\n";

        return $html;
    }
}