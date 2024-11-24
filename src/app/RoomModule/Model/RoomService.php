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

    public function updateRoom(int $id, array $data): bool
    {
        $room = $this->getTable()->get($id);
        if ($room) {
            return (bool) $room->update($data);
        }
        return false;
    }
    /**
     * Creates a new room in the database.
     *
     * @param string $name The name of the room.
     * @param int $capacity The capacity of the room.
     * @param int $creatorId The ID of the user who created the room.
     * @return ActiveRow|null The created room row, or null if creation failed.
     */
    public function createRoom(string $name, int $capacity, int $creatorId): ?ActiveRow
    {
        $data = [
            'name' => $name,
            'capacity' => $capacity,
            'creator_id' => $creatorId,
        ];

        return $this->getTable()->insert($data);
    }

    public function deleteRoomById(int $id): bool
    {
        $room = $this->getTable()->get($id);
        if ($room) {
            $room->delete();
            return true;
        }
        return false;
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