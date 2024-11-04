<?php

declare(strict_types=1);

namespace iutnc\deefy\auth;

use iutnc\deefy\exception\AuthnException;
use PDO;
use PDOException;

class AuthnProvider {
    /**
     * Méthode pour se connecter
     * @param string $email
     * @param string $password
     * @return bool
     * @throws AuthnException
     */
    public static function signin(string $email, string $password): bool{
        try{
            //Connexion à la base de données
            $pdo = new PDO('mysql:host=localhost;dbname=deefy', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT * FROM user WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            //Vérif du mdp
            if($user && isset($user['passwd']) && password_verify($password, $user['passwd'])){
                //On stock l'utilisateur dans la session
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                ];
                return true;
            }else{
                throw new AuthnException("Email ou mot de passe incorrect");
            }
        }catch(PDOException $e){
            throw new AuthnException("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    /**
     * Méthode pour enregistrer un utilisateur
     * @param string $email
     * @param string $password
     * @return bool
     * @throws AuthnException
     */
    public static function register(string $email, string $password): bool{
        try{
            //Le mdp doit avoir 10 caractères minimum
            if(strlen($password) < 10){
                throw new AuthnException("Le mot de passe doit contenir au moins 10 caractères");
            }
            //Connexion à la BDD
            $pdo = new PDO('mysql:host=localhost;dbname=deefy', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT * FROM user WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['email' => $email]);

            if($stmt->fetch(PDO::FETCH_ASSOC)){
                throw new AuthnException("Un utilisateur avec cet email existe déjà");
            }
            //Hachage du mdp
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            //Ajout de l'utilisateur dans la BDD
            $sql = "INSERT INTO user (email, passwd, role) VALUES (:email, :passwd, :role)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'email' => $email,
                'passwd' => $hashedPassword,
                'role' => 1
            ]);
            return true;
        }catch (PDOException $e){
            throw new AuthnException("Erreur lors de l'enregistrement de l'utilisateur : " . $e->getMessage());
        }
    }

    /**
     * Méthode pour avoir l'utilisateur connecté
     * @return string
     * @throws AuthnException
     */
    public static function getSignedInUser(): string {
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }
        throw new AuthnException("Aucun utilisateur connecté");
    }
}