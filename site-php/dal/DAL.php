<?php
require_once(realpath(__DIR__ . '/factories/CategoryFactory.php'));
require_once(realpath(__DIR__ . '/factories/ProductFactory.php'));

class DAL
{
    private $categoryFact = null;
    private $productFact = null;

    public function CategoryFact()
    {
        if ($this->categoryFact == null) {
            $this->categoryFact = new CategoryFactory();
        }

        return $this->categoryFact;
    }

    public function ProductFact()
    {
        if ($this->productFact == null) {
            $this->productFact = new ProductFactory();
        }

        return $this->productFact;
    }
}
