<?php

namespace iutnc\deefy\action;

use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\render\Renderer;

class DisplayPlaylistAction extends Action {

    public function execute(): string {
        /* AFFICHER LA PLAYLIST EN SESSION */
        $html = '<b>Affichage de la Playlist</b>';
        if (! isset($_SESSION['playlist'])) {
            $html .= '<b>pas de playlist</b>';
        } else {
            $pl = unserialize($_SESSION['playlist']);
            $html .= '<b>Playlist en session</b>';
            $r = new AudioListRenderer($pl);
            $html .= $r->render(Renderer::COMPACT);

        }
        return $html;
    }

}