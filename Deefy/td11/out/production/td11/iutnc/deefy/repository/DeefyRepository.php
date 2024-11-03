<?php

namespace iutnc\deefy\repository;

use PDO;

class DeefyRepository
{

    private PDO $pdo;
    private static array $config = [];
    private static ?DeefyRepository $instance = null;

    private function __construct(array $config)
    {

        $this->pdo = new PDO(
            $config['dsn'],
            $config['username'],
            $config['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    public static function setConfig($file)
    {
        $conf = parse_ini_file($file);
        self::$config = [
            'dsn' => "{$conf['driver']}:host={$conf['host']};dbname={$conf['database']}",
            'username' => $conf['username'],
            'password' => $conf['password']
        ];
    }

    public static function getInstance(): DeefyRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new self(self::$config);
        }
        return self::$instance;
    }

    /*Récupérer la liste des playlists dans la base. La méthoide retourne un tableau de Playlists ;
Les playlists ne contiennent pas les pistes. */

    /*
     * @return Playlist[]
     */
    public function getListPlaylist(): array
    {
        $sql = "SELECT * FROM playlist";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*Sauvegarder une playlist vide de pistes */
    public function savePlaylist(string $nom): void
    {
        $sql = "INSERT INTO playlist (nom) VALUES (:nom)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':nom' => $nom]);
    }
    /* Sauvegarder une piste ;*/
    public function saveTrack(string $nomFichier, string $titre, int $duree, int $idPlaylist): void
    {
        $sql = "INSERT INTO piste (nomFichier, titre, duree, idPlaylist) VALUES (:nomFichier, :titre, :duree, :idPlaylist)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':nomFichier' => $nomFichier, ':titre' => $titre, ':duree' => $duree, ':idPlaylist' => $idPlaylist]);
    }

    /*Ajouter une  piste existante à une playlist */
    public function addTrackToPlaylist(int $idPiste, int $idPlaylist): void
    {
        $sql = "INSERT INTO playlist_piste (idPlaylist, idPiste) VALUES (:idPlaylist, :idPiste)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':idPlaylist' => $idPlaylist, ':idPiste' => $idPiste]);
    }
    /*Récupérer la liste des pistes d’une playlist ; */
    public function getTracksFromPlaylist(int $idPlaylist): array
    {
        $sql = "SELECT * FROM piste WHERE idPlaylist = :idPlaylist";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':idPlaylist' => $idPlaylist]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*Supprimer une playlist ; */
    public function deletePlaylist(string $nom): void
    {
        $sql = "DELETE FROM playlist WHERE nom = :nom";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':nom' => $nom]);
    }

}







