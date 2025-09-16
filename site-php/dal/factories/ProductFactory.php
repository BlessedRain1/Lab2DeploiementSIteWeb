<?php

require_once(realpath(__DIR__ . '/base/FactoryBase.php'));
require_once(realpath(__DIR__ . '/../models/Product.php'));

class ProductFactory extends FactoryBase
{
    public function getProductById($id)
    {
        $product = null;

        $db = $this->dbConnect();
        $stmt = $db->prepare("SELECT * FROM Exercice_Produits WHERE IDModele = ?;");
        $stmt->execute(array($id));

        if ($row = $stmt->fetch()) {
            $product = new Product($row);
        }

        $stmt->closeCursor();

        return $product;
    }
    
    public function getProductsByCategory($categoryId)
    {
        $products = array();

        $db = $this->dbConnect();
        $stmt = $db->prepare("SELECT * FROM Exercice_Produits WHERE IDCategorie = ? ORDER BY NomModele;");
        $stmt->execute(array($categoryId));

        while ($row = $stmt->fetch()) {
            $products[] = new Product($row);
        }

        $stmt->closeCursor();

        return $products;
    }
}
