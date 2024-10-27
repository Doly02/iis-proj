<?php

declare(strict_types=1);

namespace App\UserModule\Controls\Register;

interface IRegisterControlFactory
{
    public function create(): RegisterControl;
}
