<?php

declare(strict_types=1);

namespace App\PresentationModule\Controls\LecturerListPresentation;

interface ILecturerListPresentationControlFactory
{
    function create(): LecturerListPresentationControl;
}