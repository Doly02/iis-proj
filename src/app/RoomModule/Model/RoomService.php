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
}