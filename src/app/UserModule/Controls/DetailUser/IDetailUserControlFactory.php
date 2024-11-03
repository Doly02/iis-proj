<?php

declare(strict_types=1);

namespace App\UserModule\Controls\DetailUser;

use Nette\Database\Table\ActiveRow;

interface IDetailUserControlFactory
{
    public function create(ActiveRow $user): DetailUserControl;
}
