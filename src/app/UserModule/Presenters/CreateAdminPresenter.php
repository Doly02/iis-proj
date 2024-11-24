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
        if ($this->getUser()->isLoggedIn() === false)
        {
            $this->redirect(':ConferenceModule:ConferenceList:list');
        }
        else if ($this->getUser()->isLoggedIn() === true && $this->template->accountType !== 'admn')
        {
            $this->redirect(':ConferenceModule:ConferenceList:list');
        }
    }

    protected function createComponentCreateAdminForm() : CreateAdminControl
    {
        return $this->_createAdminControlFactory->create();
    }
}