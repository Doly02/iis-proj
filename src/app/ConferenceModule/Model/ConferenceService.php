<?php
namespace App\ConferenceModule\Model;

use Nette\Database\Table\Selection;
use App\CommonModule\Model\BaseService;

final class ConferenceService extends BaseService
{
    public function getTableName(): string
    {
        return 'conferences';
    }

    public function getConferenceTable(): Selection
    {
        return $this->database->table('conferences');
    }

}