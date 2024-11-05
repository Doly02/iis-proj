<?php

declare(strict_types=1);

namespace App\ReservationModule\Controls\DetailReservation;

interface IDetailReservationControlFactory
{
    function create(): DetailReservationControl;
}