<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';

use iutnc\deefy\dispatch\Dispatcher;
use iutnc\deefy\exception\AuthnException;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\auth\Authz;

DeefyRepository::setConfig('db.config.ini');
session_start();
//On récup l'action
$action = $_GET['action'] ?? 'default';
$dispatcher = new Dispatcher($action);
$dispatcher->run();
$r  =  DeefyRepository::getInstance();
$pl = $r->getListPlaylist();;
$pl = $r->getListPlaylist();
if($_GET['action'] == 'default'){
    $pl = $r->getListPlaylist();
    foreach($pl as $p){
        echo $p['nom'] . '<br>';
    }
}

//EX 1 TD15
//On verifie si l'url correspond au fait de devoir afficher la playlist demandée
if($_GET['action'] === 'display-playlist' && isset($_GET['id'])){
    $playlistId = (int) $_GET['id'];
    $repository = DeefyRepository::getInstance();

    try{
        //On récup l'utilisateur connecté
        $user = AuthnProvider::getSignedInUser();

        //Vérif des autorisations
        $authz = new Authz();
        $authz->checkPlaylistOwner($playlistId, $user, $repository);

        //On récup la playlist à partir de son id
        $playlist = $repository->findPlaylistById($playlistId);

        $renderer = new AudioListRenderer($playlist);
        echo $renderer->render();

    }catch (AuthnException $e){
        echo "Erreur d'authentification : " . $e->getMessage();
    }catch (Exception $e){
        echo "Erreur : " . $e->getMessage();
    }
}