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
            SELECT r.id, r.name, r.capacity
            FROM rooms AS r
            LEFT JOIN conference_has_rooms AS chr
            ON r.id = chr.room_id
            AND NOT (chr.booking_end <= :start_time OR chr.booking_start >= :end_time)
            WHERE chr.room_id IS NULL
            OR (chr.booking_end <= :start_time OR chr.booking_start >= :end_time)
        ";

        $params = [
            'start_time' => $startTime->format('Y-m-d H:i:s'),
            'end_time' => $endTime->format('Y-m-d H:i:s')
        ];

        return $this->getTable()->fetchAll($sql, $params);
    }
}