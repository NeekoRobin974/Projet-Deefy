<?php

declare(strict_types=1);

namespace iutnc\deefy\auth;

use Exception;
use iutnc\deefy\repository\DeefyRepository;

class Authz{
    /**
     * Méthode pour vérif le rôle
     * @param int $expectedRole
     * @param string $user
     * @return void
     * @throws Exception
     */
    public function checkRole(int $expectedRole, string $user): void{
        if($user['role'] !== $expectedRole){
            throw new Exception("Accès refusé : rôle non autorisé");
        }
    }

    /**
     * Méthode pour vérif si l'utilisateur est le proprio de la playlist
     * @param int $playlistId
     * @param string $user
     * @param DeefyRepository $repository
     * @return void
     * @throws Exception
     */
    public function checkPlaylistOwner(int $playlistId, string $user, DeefyRepository $repository): void{
        //Récup la playlist à partir de son ID
        $playlist = $repository->findPlaylistById($playlistId);

        //Vérif si l'utilisateur est le propriétaire ou si son rôle est ADMIN
        if($playlist->user_id !== $user['id'] && $user['role'] !== 100){
            throw new Exception("Accès refusé : vous n'êtes pas le propriétaire de cette playlist");
        }
    }
}
