<?php

declare(strict_types=1);

namespace App\UserModule\Controls\CreateAdmin;

interface ICreateAdminControlFactory
{
    public function create(): CreateAdminControl;
}
