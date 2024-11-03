<?php

namespace iutnc\deefy\audio\lists;

class AudioList {
    protected string $nom;
    protected int $nbPistes;
    protected int $dureeTotale;
    protected array $pistes;

    public function __construct(string $nom, array $pistes = []) {
        $this->nom = $nom;
        $this->pistes = $pistes;
        $this->nbPistes = count($pistes);
        $this->dureeTotale = 0;

        // Calcul de la durée totale
        foreach ($pistes as $piste) {
            $this->dureeTotale += $piste->duree;
        }
    }

    public function __get(string $property): mixed {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        throw new \iutnc\deefy\exception\InvalidPropertyNameException("$property: invalid property");
    }

    public function __toString(): string {
        return json_encode($this);
    }
}