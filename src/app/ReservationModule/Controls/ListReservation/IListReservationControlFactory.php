<?php

declare(strict_types=1);

namespace App\ReservationModule\Controls\ListReservation;

interface IListReservationControlFactory
{
    function create(): ListReservationControl;
}