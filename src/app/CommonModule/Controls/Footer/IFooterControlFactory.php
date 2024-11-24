<?php

declare(strict_types=1);

namespace App\CommonModule\Controls\Footer;

interface IFooterControlFactory
{
    function create(): FooterControl;
}