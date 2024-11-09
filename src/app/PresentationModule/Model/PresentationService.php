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

    public function getConferencePresentations(int $conferenceId): Selection
    {
        return $this->getTable()
            ->where('conference_id', $conferenceId);
    }
}