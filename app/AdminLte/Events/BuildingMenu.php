<?php

namespace App\AdminLte\Events;

use App\AdminLte\Menu\Builder;

class BuildingMenu
{
    public $menu;

    public function __construct(Builder $menu)
    {
        $this->menu = $menu;
    }
}
