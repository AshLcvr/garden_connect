<?php

namespace App\Data ;

use App\Entity\Category;
use App\Entity\Subcategory;

class SearchData
{
    /**
     * @var integer
     */
    public $page = 1;

    /**
     * @var string
     */
    public $q = '';

    /**
     * @var Category
     */
    public $category ;

    /**
     * @var Subcategory
     */
    public $subcategory;

    /**
     * @var null|integer
     */
    public $max;

    /**
     * @var null|integer
     */
    public $min;
}