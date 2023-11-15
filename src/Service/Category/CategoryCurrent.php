<?php

namespace App\Service\Category;

use App\Entity\Category;

class CategoryCurrent{

    private Category $category;

    public function __construct()
    {
        $this->category = new Category();
    }

    public function getCategoryId():?int
    {
        return $this->category->getId();
    }

    public function setCategory(Category $category)
    {
        $this->category = $category;
    }
}

?>