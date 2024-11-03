<?php

declare(strict_types=1);

namespace iutnc\deefy\dispatch;

use Exception;
use iutnc\deefy\action\DefaultAction;
use iutnc\deefy\action\AddPlaylistAction;
use iutnc\deefy\action\AddPodcastTrackAction;
use iutnc\deefy\action\DisplayPlaylistAction;
use iutnc\deefy\action\DeletePlaylistAction;
use iutnc\deefy\action\AddUserAction;
use iutnc\deefy\action\SignInAction;
use iutnc\deefy\audio\lists\AudioList;
use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception\AuthnException;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\render\PlaylistRenderer;
use iutnc\deefy\repository\DeefyRepository;

class Dispatcher{
    private string $action; //Action à exécuter

    /**
     * Constructeur
     * @param string $action
     */
    public function __construct(string $action){
        $this->action = $action;
    }

    /**
     * Méthode pour exécuter l'action
     * @return void
     */
    public function run(): void{
        $html = ''; //html pour après

        switch($this->action){
            case 'playlist':    //Afficher la playlist
                $action = new DisplayPlaylistAction();
                $html = $action->execute();
                break;
            case 'add-playlist':    //Ajouter une playlist
                $action = new AddPlaylistAction();
                $html = $action->execute();
                break;
            case 'add-track':   //Ajouter une piste
                $action = new AddPodcastTrackAction();
                $html = $action->execute();
                break;
            case 'delete-playlist': //Supprimer une playlist
                $action = new DeletePlaylistAction();
                $html = $action->execute();
                break;
            case 'add-user':    //Ajouter un utilisateur
                $action = new AddUserAction();
                $html = $action->execute();
                break;
            case 'signin':  //Se connecter
                $action = new SignInAction();
                $html = $action->execute();
                break;
            case 'playlist-choix':  //Choisir la playlist
                $html = <<<HTML
                <h2>Entrer l'ID de la Playlist</h2>
                <form action="?action=display-playlist" method="get">
                <input type="hidden" name="action" value="display-playlist">
                <label for="playlist-id">ID de la Playlist :</label>
                <input type="number" id="playlist-id" name="id" required>
                <button type="submit">Afficher la Playlist</button>
                </form>
                HTML;
                break;
            case 'display-playlist':    //Afficher les playlist de l'utilisateur
                try{
                    //On verifie si l'utilisateur est connecté
                    $user = AuthnProvider::getSignedInUser();

                    //On vérifie si l'id de la playlist est spécifié dans l'url
                    if(isset($_GET['id'])){
                        $playlistId = (int) $_GET['id'];
                        $repository = DeefyRepository::getInstance();
                        $playlist = $repository->findPlaylistById($playlistId);

                        //On utilise le renderer afin d'afficher la playlist demandée
                        $renderer = new AudioListRenderer($playlist);
                        $html = $renderer->render();
                    }else{
                        $html = "Aucun ID de playlist spécifié, veuillez réessayer";
                    }
                }catch (AuthnException $e){
                    $html = "Erreur d'authentification : " . $e->getMessage();
                }catch (Exception $e){
                    $html = "Erreur : " . $e->getMessage();
                }
                break;
            case 'logout' : //Se deconnecter
                //Si l'utilisateur appuie sur le bouton déconnecter on fait un session destroy
                session_destroy();
                //On redirige vers la page d'accueil une fois la déconnexion effectuée
                header('Location: index.php');
                break;
            case 'afficher-user':
                try{
                    $user = AuthnProvider::getSignedInUser();

                    $repository = DeefyRepository::getInstance();
                    $id_user = $repository->RecupererIDUser($user);

                    //On récupère les playlists de l'utilisateur
                    $playlists = $repository->afficherPlUser($id_user);
                    $pseudo = explode('@', $user)[0];
                    $html = 'Liste des playlists de '.$pseudo.': ';

                    //On utilise PLaylistRenderer afin d'afficher les différentes playlists correctement
                    foreach($playlists as $playlist){
                        $renderer = new PlaylistRenderer($playlist);
                        $html .= $renderer->render();
                    }

                }catch(AuthnException $e){
                    $html = "Erreur d'authentification : " . $e->getMessage();
                }catch(Exception $e){
                    $html = "Erreur : " . $e->getMessage();
                }
                break;

            default:    //Action par défaut
                $action = new DefaultAction();
                $html = $action->execute();
                break;
        }
        $this->renderPage($html);
    }

    /**
     * Méthode pour rendre la page html
     * @param string $html
     * @return void
     */
    public function renderPage(string $html): void{
        $estConnecte = false;
        try{
            $user = AuthnProvider::getSignedInUser();
            $estConnecte = true;
            $pseudo = explode('@', $user)[0];
        }catch (Exception $e){
            $estConnecte = false;
            $pseudo = '(aucun utilisateur connecté)';
        }

        //Bouton approprié selon l'état de $estConnecte
        $DecoReco = $estConnecte
            ? '<li><a href="?action=logout">Se déconnecter</a></li>'
            : '<li><a href="?action=signin">S\'authentifier</a></li>';
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
            <li><a href="?action=playlist-choix">Playlist Par id</a></li>
            <li><a href="?action=add-playlist">Ajouter Playlist</a></li>
            <li><a href="?action=add-track">Ajouter Track</a></li>
            <li><a href="?action=add-user">Ajouter un utilisateur</a></li>
            <li><a href="?action=afficher-user">Afficher playlists utilisateur : $pseudo</a></li>
            $DecoReco
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