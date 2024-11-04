<?php

declare(strict_types=1);

namespace iutnc\deefy\repository;

use Exception;
use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\audio\tracks\AudioTrack;
use PDO;

class DeefyRepository{
    private PDO $pdo;
    private static array $config = [];
    private static ?DeefyRepository $instance = null;

    /**
     * Constructeur
     * @param array $config
     */
    private function __construct(array $config){
        $this->pdo = new PDO(
            $config['dsn'],
            $config['username'],
            $config['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    /**
     * Méthode pour définir la config de la bdd à partir du fichier .ini
     * @param $file
     * @return void
     */
    public static function setConfig($file){
        $conf = parse_ini_file($file);
        self::$config = [
            'dsn' => "{$conf['driver']}:host={$conf['host']};dbname={$conf['database']}",
            'username' => $conf['username'],
            'password' => $conf['password']
        ];
    }

    /**
     * Méthode pour obtenir l'instance unique deefyrepositery
     * @return DeefyRepository
     */
    public static function getInstance(): DeefyRepository{
        if(is_null(self::$instance)){
            self::$instance = new self(self::$config);
        }
        return self::$instance;
    }

    /**
     * Méthode qui récup toutes les playlist de la bdd
     * @return array
     */
    public function getListPlaylist(): array{
        $sql = "SELECT * FROM playlist";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Méthode pour sauvegarder une playlist dans la bdd
     * @param string $nom
     * @return void
     */
    public function savePlaylist(string $nom): void{
        $sql = "INSERT INTO playlist (nom) VALUES (:nom)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':nom' => $nom]);
    }

    /**
     * Méthode pour sauvegarder une piste audio associée à une playlist
     * @param string $nomFichier
     * @param string $titre
     * @param int $duree
     * @param int $idPlaylist
     * @return void
     */
    public function saveTrack(string $nomFichier, string $titre, int $duree, int $idPlaylist): void{
        $sql = "INSERT INTO piste (nomFichier, titre, duree, idPlaylist) VALUES (:nomFichier, :titre, :duree, :idPlaylist)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':nomFichier' => $nomFichier, ':titre' => $titre, ':duree' => $duree, ':idPlaylist' => $idPlaylist]);
    }

    /**
     * Méthode pour ajouter une piste à une playlist
     * @param int $idPiste
     * @param int $idPlaylist
     * @return void
     */
    public function addTrackToPlaylist(int $idPiste, int $idPlaylist): void{
        $sql = "INSERT INTO playlist_piste (idPlaylist, idPiste) VALUES (:idPlaylist, :idPiste)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':idPlaylist' => $idPlaylist, ':idPiste' => $idPiste]);
    }

    /**
     * Méthode qui récup toutes les pistes d'une playlist
     * @param int $idPlaylist
     * @return array
     */
    public function getTracksFromPlaylist(int $idPlaylist): array{
        $sql = "SELECT * FROM piste WHERE idPlaylist = :idPlaylist";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':idPlaylist' => $idPlaylist]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Méthode pour construire un objet playlist à partir de l'id d'une playlist
     * @param int $playlistId
     * @return Playlist
     * @throws Exception
     */
    public function findPlaylistById(int $playlistId): Playlist{
        //On récupère les informations de la playlist
        $stmt = $this->pdo->prepare("SELECT * FROM playlist WHERE id = :id");
        $stmt->execute([':id' => $playlistId]);
        $playlistData = $stmt->fetch();

        if(!$playlistData){
            throw new Exception("Playlist non trouvée");
        }

        $playlist = new Playlist($playlistData['nom'], []);

        $stmtTracks = $this->pdo->prepare("SELECT * FROM playlist2track WHERE id_pl = :id");
        $stmtTracks->execute([':id' => $playlistId]);

        while($trackData = $stmtTracks->fetch()){
            $trackStmt = $this->pdo->prepare("SELECT * FROM track WHERE id = :id");
            $trackStmt->execute([':id' => $trackData['id_track']]);
            $trackDetails = $trackStmt->fetch();

            $track = new AudioTrack(
                $trackDetails['titre'],
                $trackDetails['filename'],
                (int) $trackDetails['duree']
            );

            //Ajouter la piste à la playlist
            $playlist->ajouterPiste($track);
        }
        return $playlist;
    }

    /**
     * Méthode pour ajouter une playlist
     * @param string $playlist_name
     * @return void
     * @throws Exception
     */
    public function ajouterPlaylist(string $playlist_name): void{
        $sql = "INSERT INTO playlist (nom) VALUES (:nom)";
        $stmt = $this->pdo->prepare($sql);
        try{
            $stmt->execute([
                ':nom' => $playlist_name
            ]);
        }catch(Exception $e){
            throw new Exception("Erreur lors de l'ajout de la playlist : " . $e->getMessage());
        }
    }

    /**
     * Méthode pour ajouter une relation entre un utilisateur et une playlist
     * @param int $id_user
     * @param $id_pl
     * @return void
     * @throws Exception
     */
    public function ajouteruser2playlist(int $id_user,$id_pl): void{
        $sql = "INSERT INTO user2playlist (id_user,id_pl) VALUES (:id_user,:id_pl)";
        $stmt = $this->pdo->prepare($sql);
        try{
            $stmt->execute([
                ':id_user' => $id_user,
                ':id_pl' => $id_pl
            ]);
        }catch(Exception $e){
            throw new Exception("Erreur lors de l'ajout de la playlist : " . $e->getMessage());
        }
    }

    /**
     * Méthode pour récup l'id d'un utilisateur
     * @param string $email
     * @return int|null
     * @throws Exception
     */
    public function RecupererIDUser(string $email): ?int{
        $sql = "SELECT id FROM user WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        try{
            $stmt->execute([
                ':email' => $email
            ]);
            $row = $stmt->fetch();
            if($row){
                return (int) $row['id'];
            }else{
                return null;
            }
        }catch (Exception $e){
            throw new Exception("Erreur lors de la récupération de l'ID de l'utilisateur : " . $e->getMessage());
        }
    }

    /**
     * Méthode pour récup l'id d'une playlist
     * @param string $nompl
     * @return int|null
     * @throws Exception
     */
    public function RecupererIDPL(string $nompl): ?int{
        $sql = "SELECT id FROM playlist WHERE nom = :nompl";
        $stmt = $this->pdo->prepare($sql);
        try{
            $stmt->execute([
                ':nompl' => $nompl
            ]);
            $row = $stmt->fetch();
            if($row){
                return (int) $row['id'];
            } else {
                return null;
            }
        }catch(Exception $e){
            throw new Exception("Erreur lors de la récupération de l'ID de la playlist : " . $e->getMessage());
        }
    }

    /**
     * Méthode pour afficher toutes les playlist d'un utilisateur
     * @param int $id_user
     * @return array
     */
    public function afficherPlUser(int $id_user): array {
        $sql = "SELECT pl.nom FROM playlist pl INNER JOIN user2playlist u2pl ON pl.id = u2pl.id_pl WHERE u2pl.id_user = :id_user";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_user' => $id_user]);
        $playlists = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            //On crée un objet playlist pour chaque ligne qu'on récupère dans la BDD
            $playlists[] = new Playlist($row['nom'], []);
        }
        return $playlists;
    }
}