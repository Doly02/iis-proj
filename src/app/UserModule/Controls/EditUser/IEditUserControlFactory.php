<?php

declare(strict_types=1);

namespace App\UserModule\Controls\EditUser;

interface IEditUserControlFactory
{
    function create(): \App\UserModule\Controls\Login\EditUserControl;
}
