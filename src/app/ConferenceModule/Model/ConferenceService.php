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
        return $this->database->table('conferences');
    }

    public function addConference(array $data): ?ActiveRow
    {
        $insertedRow = $this->getTable()->insert($data);

        return $insertedRow instanceof ActiveRow ? $insertedRow : null;
    }

}