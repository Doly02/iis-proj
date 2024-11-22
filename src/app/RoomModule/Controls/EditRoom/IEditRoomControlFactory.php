<?php

declare(strict_types=1);

namespace App\RoomModule\Controls\EditRoom;

use Nette\Database\Table\ActiveRow;

interface IEditRoomControlFactory
{
    public function create(int $roomId): EditRoomControl;
}