<?php

declare(strict_types=1);

namespace iutnc\deefy\action;

use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception\AuthnException;

class SignInAction extends Action{
    /**
     * Méthode qui execute la connexion d'un utilisateur
     * @return string
     */
    public function execute(): string{
        $html = '<b>Connexion</b>';
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $html .= <<<HTML
            <form method="post" action="?action=signin">
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required>
                
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
                
                <button type="submit">Se connecter</button>
            </form>
            HTML;

        }elseif($_SERVER['REQUEST_METHOD'] === 'POST'){
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
            try{
                //Connexion réussie
                if(AuthnProvider::signin($email, $password)){
                    $_SESSION['user'] = $email;
                    $html .= '<p>Authentification réussie. Bienvenue, ' . htmlspecialchars($email) . '!</p>';
                }
            //Erreur à la connexion
            }catch (AuthnException $e){
                $html .= '<p>Erreur d\'authentification : ' . htmlspecialchars($e->getMessage()) . '</p>';
            }
        }
        return $html;
    }
}