<?php

declare(strict_types=1);

namespace iutnc\deefy\action;

use Exception;
use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\exception\AuthnException;

class AddPlaylistAction extends Action{
    /**
     * Méthode qui execute l'ajout de playlist
     * @return string
     */
    public function execute(): string{
        $html = ''; //Stock l'affichage html pour après
        try{
            //On vérifie si l'utilisateur est connecté
            $user = AuthnProvider::getSignedInUser();

            //Affichage du formulaire
            if($_SERVER['REQUEST_METHOD'] === 'GET'){
                $html .= <<<HTML
                <h2>Créer une nouvelle playlist</h2>
                <form method="post" action="?action=add-playlist">
                    <label for="playlist_name">Nom de la playlist :</label>
                    <input type="text" id="playlist_name" name="playlist_name" required>
                    <button type="submit">Créer Playlist</button>
                </form>
                HTML;

            }elseif($_SERVER['REQUEST_METHOD'] === 'POST'){
                //On récupère le nom de la playlist
                $playlist_name = filter_var($_POST['playlist_name'], FILTER_SANITIZE_STRING);

                //On crée l'objet playlist correspondant
                $playlist = new Playlist($playlist_name, []);

                //On insère dans la BDD
                $repository = DeefyRepository::getInstance();
                $repository->ajouterPlaylist($playlist_name);
                $id_pl = $repository->RecupererIDPL($playlist_name);
                $id_user = $repository->RecupererIDUser($user);
                $repository->ajouteruser2playlist($id_user,$id_pl);

                //On la stocke en session
                $_SESSION['playlist'] = serialize($playlist);

                //On affiche la playlist
                $renderer = new AudioListRenderer($playlist);
                $html .= $renderer->render();
                $html .= '<a href="?action=add-track">Ajouter une piste</a>';
            }
        }catch (AuthnException $e){
            $html = "Erreur d'authentification : " . $e->getMessage();
        }catch (Exception $e){
            $html = "Erreur : " . $e->getMessage();
        }
        return $html;
    }
}