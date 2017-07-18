<?php

namespace App\AdminLte\Menu\Filters;

use App\AdminLte\Menu\Builder;

interface FilterInterface
{
    public function transform($item, Builder $builder);
}
