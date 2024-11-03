<?php

declare(strict_types=1);

namespace iutnc\deefy\audio\lists;

class AudioList{
    protected string $nom;  //Nom de la liste
    protected int $nbPistes;    //Nombre de pistes
    protected int $dureeTotale; //Durée totale
    protected array $pistes;    //Tableau contenant les pistes

    /**
     * Constructeur
     * @param string $nom
     * @param array $pistes
     */
    public function __construct(string $nom, array $pistes = []){
        $this->nom = $nom;
        $this->pistes = $pistes;
        $this->nbPistes = count($pistes);
        $this->dureeTotale = 0;
        //Calcul de la durée totale
        foreach ($pistes as $piste) {
            $this->dureeTotale += $piste->duree;
        }
    }

    /**
     * Méthode __get magique pour accéder aux propriétés de la classe
     * @param string $property
     * @return mixed
     * @throws \iutnc\deefy\exception\InvalidPropertyNameException
     */
    public function __get(string $property): mixed{
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        throw new \iutnc\deefy\exception\InvalidPropertyNameException("$property: invalid property");
    }

    /**
     * Méthode toString pour convertir l'objet en chaîne JSON
     * @return string
     */
    public function __toString(): string{
        return json_encode($this);
    }
}