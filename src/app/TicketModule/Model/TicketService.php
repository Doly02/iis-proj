<?php

namespace App\TicketModule\Model;

use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;
use Nette\Utils\ArrayHash;
use App\CommonModule\Model\BaseService;
use Nette\Security\SimpleIdentity;
use Exception;
use Tracy\Debugger;

final class TicketService extends BaseService
{
    public function getTableName(): string
    {
        return 'tickets';
    }
}