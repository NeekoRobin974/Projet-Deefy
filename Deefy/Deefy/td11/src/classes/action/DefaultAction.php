<?php

declare(strict_types=1);

namespace iutnc\deefy\action;

class DefaultAction extends Action{
    /**
     * Action par défaut
     * @return string
     */
    public function execute(): string{
        return '<h3>Liste des playlists globales : </h3>';
    }
}