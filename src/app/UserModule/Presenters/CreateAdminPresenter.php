<?php

namespace App\UserModule\Presenters;

use App\CommonModule\Presenters\SecurePresenter;
use App\UserModule\Controls\CreateAdmin\CreateAdminControl;
use App\UserModule\Controls\CreateAdmin\ICreateAdminControlFactory;

final class CreateAdminPresenter extends SecurePresenter
{
    private ICreateAdminControlFactory $_createAdminControlFactory;


    public function __construct(
        ICreateAdminControlFactory $createAdminControlFactory
    )
    {
        parent::__construct();
        $this->_createAdminControlFactory = $createAdminControlFactory;
    }

    public function actionDefault(): void
    {

    }

    protected function createComponentCreateAdminForm() : CreateAdminControl
    {
        return $this->_createAdminControlFactory->create();
    }
}