<?php

namespace iutnc\deefy\action;

class DeletePlaylistAction extends Action {

    public function execute(): string {
        /* SUPPRIMER LA PLAYLIST EN SESSION */
        $html = '<b>Suppression de la Playlist</b>';
        if (! isset($_SESSION['playlist'])) {
            $html .= '<b>pas de playlist</b>';
        } else {
            unset($_SESSION['playlist']);
            $html .= '<b>Playlist supprimee</b>';
        }
        return $html;
    }

}
