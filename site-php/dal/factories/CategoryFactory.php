<?php

require_once(realpath(__DIR__ . '/base/FactoryBase.php'));
require_once(realpath(__DIR__ . '/../models/Category.php'));

class CategoryFactory extends FactoryBase
{
    public function getAllCategories()
    {
        $categories = array();

        $db = $this->dbConnect();
        $stmt = $db->prepare('SELECT * FROM Exercice_Categories ORDER BY Nom;');
        $stmt->execute();

        while ($row = $stmt->fetch()) {
            $categories[$row["ID"]] = new Category($row);
        }

        $stmt->closeCursor();

        return $categories;
    }
}
