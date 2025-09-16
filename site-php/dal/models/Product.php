<?php

class Product
{
    public $id;
    public $nom;
    public $deplacement;
    public $cylindre;
    public $transmission;
    public $traction;
    public $carburant;
    public $id_categorie;
    public $ville_mpg;
    public $route_mpg;
    public $combine_mpg;
    public $ville;
    public $route;
    public $combine;

    public function __construct($sql_row)
    {
        if (isset($sql_row)) {
            $this->id = $sql_row["IDModele"];
            $this->nom = $sql_row["NomModele"];
            $this->deplacement = $sql_row["Deplacement"];
            $this->cylindre = $sql_row["Cylindre"];
            $this->transmission = $sql_row["Transmission"];
            $this->traction = $sql_row["Traction"];
            $this->carburant = $sql_row["Carburant"];
            $this->id_categorie = $sql_row["IDCategorie"];
            $this->ville_mpg = $sql_row["CityMPG"];
            $this->route_mpg = $sql_row["HwyMPG"];
            $this->combine_mpg = $sql_row["CmbMPG"];
            $this->ville = $sql_row["Ville"];
            $this->route = $sql_row["Route"];
            $this->combine = $sql_row["Combine"];
        }
    }
}
