<?php

declare(strict_types=1);

namespace App\PresentationModule\Controls\ListPresentation;

interface IListPresentationControlFactory
{
    function create(): ListPresentationControl;
}