<?php

declare(strict_types=1);

namespace App\ReservationModule\Controls\ReserveNonRegistered;

interface IReserveNonRegisteredControlFactory
{
    function create(): \App\ReservationModule\Controls\ReserveNonRegistered\ReserveNonregisteredControl;
}
