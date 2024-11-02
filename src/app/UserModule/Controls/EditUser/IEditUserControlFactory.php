<?php

declare(strict_types=1);

namespace App\UserModule\Controls\EditUser;

use Nette\Database\Table\ActiveRow;

interface IEditUserControlFactory
{
    public function create(ActiveRow $user): EditUserControl;
}
