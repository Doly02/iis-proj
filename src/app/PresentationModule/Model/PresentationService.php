<?php
namespace App\PresentationModule\Model;

use App\UserModule\Model\Role;
use Nette\Database\Table\Selection;
use App\CommonModule\Model\BaseService;
use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;
use Tracy\Debugger;
use Exception;

final class PresentationService extends BaseService
{
    public function __construct(Explorer $database)
    {
        parent::__construct($database);
    }

    public function getTableName(): string
    {
        return 'Presentations';
    }

    public function addPresentation(array $data): ?ActiveRow
    {
        $insertedRow = $this->getTable()->insert($data);

        return $insertedRow instanceof ActiveRow ? $insertedRow : null;
    }

    public function getConferencePresentations(int $conferenceId): array
    {
        $sql = "
        SELECT 
            presentations.*,
            conferences.name AS conference_name
        FROM 
            presentations
        JOIN 
            conferences ON presentations.conference_id = conferences.id
        WHERE 
            presentations.conference_id = ?
        ";

        return $this->database->query($sql, $conferenceId)->fetchAll();
    }

    public function getOrganizerPresentations(int $userId): array
    {
        $sql = "
        SELECT 
            presentations.*,
            conferences.name AS conference_name
        FROM 
            presentations
        JOIN 
            conferences ON presentations.conference_id = conferences.id
        WHERE 
            conferences.organiser_id = ?
        ";

        return $this->database->query($sql, $userId)->fetchAll();
    }

    public function approvePresentationById(int $id): bool
    {
        return $this->updatePresentationState($id, 'approved');
    }

    public function denyPresentationById(int $id): bool
    {
        return $this->updatePresentationState($id, 'denied');
    }

    private function updatePresentationState(int $id, string $state): bool
    {
        $row = $this->getTable()->get($id);

        return (bool) $row->update(['state' => $state]);
    }
}