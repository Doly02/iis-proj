<?php

declare(strict_types=1);

namespace App\ReservationModule\Controls\ListReservationAttendant;

interface IListReservationAttendantControlFactory
{
    public function create(): ListReservationAttendantControl;
}
