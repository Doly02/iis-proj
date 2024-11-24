<?php

declare(strict_types=1);

namespace App\CommonModule\Controls\SideBar;

interface ISideBarControlFactory
{
    public function create(string $accountType): SideBarControl;

}