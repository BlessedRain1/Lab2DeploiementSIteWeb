<?php

class Category
{
    public $id;
    public $nom;

    public function __construct($sql_row)
    {
        if (isset($sql_row)) {
            $this->id = $sql_row["ID"];
            $this->nom = $sql_row["Nom"];
        }
    }
}
