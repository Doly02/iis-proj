<?php

declare(strict_types=1);

namespace App\ReservationModule\Controls\ReserveNonRegistered;

use \App\ReservationModule\Controls\ReserveNonRegistered\ReserveNonregisteredControl;

interface IReserveNonRegisteredControlFactory
{
    function create() : ReserveNonregisteredControl;
}
