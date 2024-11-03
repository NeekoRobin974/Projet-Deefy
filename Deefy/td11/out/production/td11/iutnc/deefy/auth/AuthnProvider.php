<?php

namespace iutnc\deefy\auth;
/*  Créer la classe iutnc\deefy\auth\AuthnProvider qui va regrouper l'ensemble des
méthodes liées à l'authentification. L'authentification utilise la table user qui stocke l'identifiant de
chaque utilisateur (email) et son mot de passe encodé avec bcrypt (password_hash()).
 Créer la méthode (statique) signin() qui reçoit l'email et le mot de passe en clair d'un utilisateur,
et contrôle la validité de ces données.*/
class AuthnProvider
{
    public static function signin(string $email, string $password): bool
    {
        $pdo = new \PDO('mysql:host=localhost;dbname=deefy', 'root', '');
        $sql = "SELECT * FROM user WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            return true;
        }
        return false;
    }
}
