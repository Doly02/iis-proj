<?php

declare(strict_types=1);

namespace App\RoomModule\Controls\AddRoom;;

interface IAddRoomControlFactory
{
    function create(): \App\RoomModule\Controls\AddRoom\AddRoomControl;
}
