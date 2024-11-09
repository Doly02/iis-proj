<?php

declare(strict_types=1);

namespace App\RoomModule\Controls\ListRoom;


interface IListRoomControlFactory
{
    function create(): ListRoomControl;
}
