<?php

namespace App\Data;

use App\Entity\Category;

class SearchData
{
    /**
     * @var string|null
     */
    public ?string $q = '';

    /**
     * @var Category[]
     */
    public array $categories = [];

    /**
     * @var int
     */
    public int $page = 1;

}