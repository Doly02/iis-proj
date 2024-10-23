<?php

namespace App\UserModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use App\UserModule\Controls\Register\IRegisterControlFactory;
use App\UserModule\Controls\Register\RegisterControl;


final class RegisterPresenter extends BasePresenter
{
    private IRegisterControlFactory $registerControlFactory;


    public function __construct(
        IRegisterControlFactory $registerControlFactory
    )
    {
        parent::__construct();
        $this->registerControlFactory = $registerControlFactory;
    }

    public function actionDefault(): void
    {
        if ($this->getUser()->isLoggedIn())
        {
            $this->redirect(':CommonModule:Home:default');
        }
    }
    protected function createComponentRegisterForm(): RegisterControl
    {
        return $this->registerControlFactory->create();
    }
}