<?php

namespace iutnc\deefy\audio\lists;

class Playlist extends AudioList {
    public function ajouterPiste(\iutnc\deefy\audio\tracks\AudioTrack $piste): void {
        $this->pistes[] = $piste;
        $this->nbPistes++;
        $this->dureeTotale += $piste->duree;
    }

    public function supprimerPiste(int $index): void {
        if ($index < 0 || $index >= $this->nbPistes) {
            throw new \Exception("Invalid track index.");
        }
        $this->dureeTotale -= $this->pistes[$index]->duree;
        array_splice($this->pistes, $index, 1);
        $this->nbPistes--;
    }

    public function ajouterListePistes(array $nouvellesPistes): void {
        foreach ($nouvellesPistes as $nouvellePiste) {
            // Vérifier les doublons par le titre et le nom de fichier
            $doublon = false;
            foreach ($this->pistes as $pisteExistante) {
                if ($pisteExistante->titre === $nouvellePiste->titre && $pisteExistante->nomFichier === $nouvellePiste->nomFichier) {
                    $doublon = true;
                    break;
                }
            }
            if (!$doublon) {
                $this->ajouterPiste($nouvellePiste);
            }
        }
    }

    public function __toString(): string {
        return json_encode([
            'nom' => $this->nom,
            'nbPistes' => $this->nbPistes,
            'dureeTotale' => $this->dureeTotale,
            'pistes' => $this->pistes,
        ]);
    }
}