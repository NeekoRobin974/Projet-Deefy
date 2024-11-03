<?php

namespace iutnc\deefy\dispatch;

use http\QueryString;
use iutnc\deefy\action\DefaultAction;
use iutnc\deefy\action\AddPlaylistAction;
use iutnc\deefy\action\AddPodcastTrackAction;
use iutnc\deefy\action\DisplayPlaylistAction;
use iutnc\deefy\action\DeletePlaylistAction;
use iutnc\deefy\action\AddUserAction;


class Dispatcher
{


    private string $action;

    public function __construct(string $action)
    {
        $this->action = $action;
    }

    public function run(): void
    {
        /*
            ?action=default
            ?action=playlist
            ?action=add-playlist
            ?action=add-track
            ?action=delete-playlist
            ?action=add-user
        */
        switch ($this->action) {
            case 'playlist':
                $action = new DisplayPlaylistAction();
                $html = $action->execute();
                break;
            case 'add-playlist':
                $action = new AddPlaylistAction();
                $html = $action->execute();
                break;
            case 'add-track':
                $action = new AddPodcastTrackAction();
                $html = $action->execute();
                break;
            case 'delete-playlist':
                $action = new DeletePlaylistAction();
                $html = $action->execute();
                break;
            case 'add-user':
                $action = new AddUserAction();
                $html = $action->execute();
                break;

            default:
                $action = new DefaultAction();
                $html = $action->execute();
                break;
        }
        $this->renderPage($html);
    }

    public function renderPage(string $html): void
    {
        echo <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deefy</title>
</head>
<body>
    <h1>Deefy</h1>
    <nav>
        <ul>
            <li><a href="?action=default">Accueil</a></li>
            <li><a href="?action=playlist">Playlist</a></li>
            <li><a href="?action=add-playlist">Ajouter Playlist</a></li>
            <li><a href="?action=add-track">Ajouter Track</a></li>
            <li><a href="?action=delete-playlist">Supprimer la playlist</a> </li>
            <li><a href="?action=add-user">Ajouter un utilisateur</a></li>
        </ul>
    </nav>
    <main>
        $html
    </main>
</body>
</html>
HTML;
    }


}