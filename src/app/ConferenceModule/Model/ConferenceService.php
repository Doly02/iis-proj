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

    public function deleteConferenceById(int $id): void
    {
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
        // Získanie konferencií pre organizátora
        $conferences = $this->database->table('conferences')
            ->where('organiser_id', $organiserId)
            ->fetchAll();

        // Získanie obsadených kapacít
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



}
