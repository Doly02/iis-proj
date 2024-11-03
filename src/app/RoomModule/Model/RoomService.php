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
    /*
    public function getAvailableRoomsWithTimes(\DateTimeImmutable $startTime, \DateTimeImmutable $endTime): array
    {
        $rooms = $this->fetchAvailableRooms($startTime, $endTime);
        $availableRooms = [];

        foreach ($rooms as $room) {
            $roomId = $room->id;
            $roomName = $room->name;

            if (!isset($availableRooms[$roomId])) {
                $availableRooms[$roomId] = [
                    'name' => $roomName,
                    'available_times' => []
                ];
                $lastEnd = $startTime;
            }

            $bookingStart = $room->booking_start ? new \DateTimeImmutable($room->booking_start) : null;
            $bookingEnd = $room->booking_end ? new \DateTimeImmutable($room->booking_end) : null;

            if ($bookingStart && $lastEnd < $bookingStart) {
                $availableRooms[$roomId]['available_times'][] = [
                    'start' => $lastEnd,
                    'end' => $bookingStart
                ];
            }

            $lastEnd = $bookingEnd ?? $lastEnd;
        }

        foreach ($availableRooms as &$room) {
            if ($lastEnd < $endTime) {
                $room['available_times'][] = [
                    'start' => $lastEnd,
                    'end' => $endTime
                ];
            }
        }

        return $availableRooms;
    }*/
}