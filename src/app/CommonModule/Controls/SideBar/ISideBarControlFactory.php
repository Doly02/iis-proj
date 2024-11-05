<?php

declare(strict_types=1);

namespace App\CommonModule\Controls\SideBar;

interface ISideBarControlFactory
{
    function create(): SideBarControl;
}