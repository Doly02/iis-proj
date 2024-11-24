<?php

namespace App\ConferenceModule\Model;

use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;
use App\CommonModule\Model\BaseService;
use App\RoomModule\Model\RoomService;

final class ConferenceService extends BaseService
{
    public function __construct(Explorer $database)
    {
        parent::__construct($database);
    }

    public function getTableName(): string
    {
        return 'conferences';
    }

    public function getConferenceTable(): Selection
    {
        return $this->database->table($this->getTableName());
    }

    public function addConference(array $data): ?ActiveRow
    {
        $insertedRow = $this->getTable()->insert($data);

        return $insertedRow instanceof ActiveRow ? $insertedRow : null;
    }

    public function getConferenceById(int $conferenceId): ?ActiveRow
    {
        return $this->getConferenceTable()->get($conferenceId);
    }

    public function updateConferenceCapacity(int $conferenceId, int $newCapacity): void
    {

        $this->getTable()
            ->where('id = ?', $conferenceId)
            ->update(['capacity' => $newCapacity]);
    }

    public function updateConference(int $conferenceId, array $data): void
    {
        $this->getTable()
            ->where('id = ?', $conferenceId)
            ->update($data);
    }

    public function getReservationsCount(int $conferenceId): int
    {
        return $this->database->table('reservations')
            ->where('conference_id', $conferenceId)
            ->count('*');
    }

    public function getRoomsCount(int $conferenceId): int
    {
        return $this->database->table('conference_has_rooms')
            ->where('conference_id', $conferenceId)
            ->count('*');
    }

    public function getLecturesCount(int $conferenceId): int
    {
        $sql = "
        SELECT COUNT(*) AS lecture_count
        FROM lectures l
        JOIN conference_has_rooms chr ON l.id_conference_has_rooms = chr.id
        WHERE chr.conference_id = ?
    ";

        return $this->database->query($sql, $conferenceId)->fetchField();
    }


    public function deleteConferenceById(int $id): void
    {
        $reservations = $this->database->table('reservations')
            ->where('conference_id', $id)
            ->count('*');

        if ($reservations > 0) {
            throw new \Exception('The conference cannot be deleted because it has existing reservations.');
        }

        $conferenceHasRooms = $this->database->table('conference_has_rooms')
            ->where('conference_id', $id);

        foreach ($conferenceHasRooms as $room) {
            $this->database->table('lectures')
                ->where('id_conference_has_rooms', $room->id)
                ->delete();
        }

        $conferenceHasRooms->delete();

        $this->database->table('conferences')
            ->where('id', $id)
            ->delete();
    }



    public function getOccupiedCapacity(): array
    {
        return $this->database->table('reservations')
            ->select('conference_id, SUM(num_reserved_tickets) AS occupied_capacity')
            ->group('conference_id')
            ->fetchPairs('conference_id', 'occupied_capacity');
    }

    public function getConferenceTableWithCapacity(): array
    {
        $conferences = $this->getConferenceTable()->fetchAll();
        $occupiedCapacities = $this->getOccupiedCapacity();

        $result = [];
        foreach ($conferences as $conference) {
            $result[] = [
                'id' => $conference->id,
                'name' => $conference->name,
                'start_time' => $conference->start_time,
                'price' => $conference->price,
                'area_of_interest' => $conference->area_of_interest,
                'capacity' => $conference->capacity,
                'occupied_capacity' => $occupiedCapacities[$conference->id] ?? 0,
            ];
        }

        return $result;
    }

    public function getConferencesWithCapacityByOrganiser(int $organiserId): array
    {
        $conferences = $this->database->table('conferences')
            ->where('organiser_id', $organiserId)
            ->fetchAll();

        $occupiedCapacities = $this->getOccupiedCapacity();

        $result = [];
        foreach ($conferences as $conference) {
            $result[] = [
                'id' => $conference->id,
                'name' => $conference->name,
                'start_time' => $conference->start_time,
                'end_time' => $conference->end_time,
                'price' => $conference->price,
                'capacity' => $conference->capacity,
                'area_of_interest' => $conference->area_of_interest,
                'occupied_capacity' => $occupiedCapacities[$conference->id] ?? 0,
            ];
        }

        return $result;
    }

    public function getRoomNamesByConferenceId(int $conferenceId): array
    {
        $roomIds = $this->database->table('conference_has_rooms')
            ->where('conference_id', $conferenceId)
            ->fetchPairs(null, 'room_id');

        if (empty($roomIds)) {
            return [];
        }

        return $this->database->table('rooms')
            ->where('id', $roomIds)
            ->fetchPairs('id', 'name');
    }

    public function hasUserReservedConference(int $userId, int $conferenceId): bool
    {
        return $this->database->table('reservations')
                ->where('customer_id', $userId)
                ->where('conference_id', $conferenceId)
                ->count('*') > 0;
    }

}
