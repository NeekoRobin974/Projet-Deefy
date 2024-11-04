<?php

declare(strict_types=1);

namespace iutnc\deefy\render;

use iutnc\deefy\audio\lists\Playlist;

class PlaylistRenderer implements Renderer{
    private Playlist $playlist;

    /**
     * Constructeur
     * @param Playlist $playlist
     */
    public function __construct(Playlist $playlist){
        $this->playlist = $playlist;
    }

    /**
     * Méthode pour rendre la playlist en html
     * @param int $selector
     * @return string
     */
    public function render(int $selector = Renderer::COMPACT): string{
        //On affiche le nom de la playlist
        $html = "<h2>" . htmlspecialchars($this->playlist->nom) . "</h2>\n";
        $html .= "<ul>\n";

        //S'il y a des pistes on les affiches
        if(!empty($this->playlist->pistes)){
            foreach ($this->playlist->pistes as $piste) {
                $html .= "<li>" . htmlspecialchars($piste->titre) . " (" . $piste->duree . " secondes)</li>\n"; // Affiche le titre et la durée
                $html .= "<audio controls>\n";
                $html .= "<source src='./audio/" . htmlspecialchars($piste->nomFichier) . "' type='audio/mpeg'>\n"; // Lien vers le fichier audio
                $html .= "Votre navigateur ne supporte pas l'élément audio.\n";
                $html .= "</audio>\n";
            }
        }else{
            //Sinon(Si aucune piste dans la playlist):
            $html .= "<li>Aucune piste disponible dans cette playlist.</li>\n";
        }

        $html .= "</ul>\n";
        //On affiche le nombre de pistes de la playlist
        $html .= "<p><strong>Nombre de pistes :</strong> " . count($this->playlist->pistes) . "</p>\n";
        return $html;
    }
}