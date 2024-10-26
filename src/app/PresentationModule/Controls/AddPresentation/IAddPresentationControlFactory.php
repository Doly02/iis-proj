<?php

declare(strict_types=1);

namespace App\PresentationModule\Controls\AddPresentation;

interface IAddPresentationControlFactory
{
    function create(): \App\PresentationModule\Controls\AddPresentation\AddPresentationControl;
}
