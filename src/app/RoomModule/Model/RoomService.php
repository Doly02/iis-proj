<?php
namespace App\RoomModule\Model;

use Nette\Database\Table\Selection;
use App\CommonModule\Model\BaseService;
use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;

final class RoomService extends BaseService
{
    public function __construct(Explorer $database)
    {
        parent::__construct($database);
    }

    public function getTableName(): string
    {
        return 'rooms';
    }

    public function addRoom(array $data): ?ActiveRow
    {
        return $this->getTable()->insert($data);
    }

    public function fetchAvailableRooms(\DateTimeImmutable $startTime, \DateTimeImmutable $endTime): array
    {
        $sql = "
        SELECT rooms.id, rooms.name, rooms.capacity
        FROM rooms
        LEFT JOIN conference_has_rooms
            ON rooms.id = conference_has_rooms.room_id
            AND NOT (conference_has_rooms.booking_end <= ? OR conference_has_rooms.booking_start >= ?)
        WHERE conference_has_rooms.room_id IS NULL
    ";

        return $this->database->query($sql, $startTime, $endTime)->fetchAll();
    }

}