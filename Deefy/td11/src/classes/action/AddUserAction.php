<?php

declare(strict_types=1);

namespace iutnc\deefy\action;

use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception\AuthnException;

class AddUserAction extends Action{
    /**
     * Méthode qui execute l'ajout d'un utilisateur
     * @return string
     */
    public function execute(): string{
        $html = '<b>Inscription d\'un utilisateur</b>';
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $html .= <<<HTML
            <form method="post" action="?action=add-user">
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required>
                
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
                
                <button type="submit">S\'inscrire</button>
            </form>
            HTML;

        }elseif($_SERVER['REQUEST_METHOD'] === 'POST'){
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
            try{
                //Inscription réussie
                if(AuthnProvider::register($email, $password)){
                    $html .= '<p>Inscription réussie pour l\'utilisateur ' . htmlspecialchars($email) . '.</p>';
                }
            //Problème à l'inscription
            }catch(AuthnException $e){
                $html .= '<p>Erreur lors de l\'inscription : ' . htmlspecialchars($e->getMessage()) . '</p>';
            }
        }
        return $html;
    }
}