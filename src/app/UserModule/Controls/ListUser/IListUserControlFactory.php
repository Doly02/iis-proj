<?php

declare(strict_types=1);

namespace App\UserModule\Controls\ListUser;

interface IListUserControlFactory
{
    public function create(): ListUserControl;
}
