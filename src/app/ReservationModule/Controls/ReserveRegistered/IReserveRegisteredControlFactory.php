<?php

declare(strict_types=1);

namespace App\ReservationModule\Controls\ReserveRegistered;

use \App\ReservationModule\Controls\ReserveRegistered\ReserveRegisteredControl;
interface IReserveRegisteredControlFactory
{
    function create() : ReserveRegisteredControl;
}
