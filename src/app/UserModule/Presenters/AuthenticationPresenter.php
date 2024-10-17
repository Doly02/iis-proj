<?php

namespace App\UserModule\Presenters;

use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Security\AuthenticationException;
use App\UserModule\Model\FakeAuthenticator;

final class AuthenticationPresenter extends Presenter
{
    /**
     * @var \App\UserModule\Controls\Login\ILoginControlFactory
     */
    private $loginControlFact;


    public function __construct(\App\UserModule\Controls\Login\ILoginControlFactory $loginControlFactory)
    {
        parent::__construct();
        $this->loginControlFact = $loginControlFactory;
    }

    public function actionSignIn(): void
    {
        \Tracy\Debugger::log('SignIn action loaded');
        if ($this->getUser()->isLoggedIn()) {
            $this->redirect(':Core:Homepage:default');
        }
    }

    protected function createComponentLogin(): \App\UserModule\Controls\Login\LoginControl
    {
        return $this->loginControlFact->create();
    }
}
