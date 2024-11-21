<?php

declare(strict_types=1);

namespace App\ReservationModule\Controls\ListReservationOrganizer;

interface IListReservationOrganizerControlFactory
{
    public function create(): ListReservationOrganizerControl;
}
