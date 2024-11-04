<?php

namespace iutnc\deefy\action;

class AddUserAction extends Action {

    public function execute(): string {
        /* AJOUTER UN UTILISATEUR */
        $html = '<b>Ajout d\'un utilisateur</b>';
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $html .= <<<HTML
            <form method="post" action="?action=add-user">
                <label for="name">Nom de l'utilisateur :</label>
                <input type="text" id="name" name="name" required>
                
                <label for="email">Email :</label>
                <input type="text" id="email" name="email" required>
                
                <label for="age">Age :</label>
                <input type="number" id="age" name="age" required>
                
                <button type="submit">Connexion</button>
            </form>
            HTML;

        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
            $age = filter_var($_POST['age'], FILTER_SANITIZE_NUMBER_INT);

            $users = [];
            $users[] = ['name' => $name, 'email' => $email, 'age' => $age];
            $_SESSION['users'] = serialize($users);
            $html .= '<b> Utilisateur ajouté : </b>'. '<p><strong>Nom</strong> : ' . $name .'  <strong>email</strong> : ' . $email. ' <strong>age</strong> :' . $age.'</p>';

        }
        return $html;
    }

}