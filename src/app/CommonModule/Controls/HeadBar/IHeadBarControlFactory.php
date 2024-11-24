<?php

declare(strict_types=1);

namespace App\CommonModule\Controls\HeadBar;

interface IHeadBarControlFactory
{
    function create(): HeadBarControl;
}