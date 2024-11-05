<?php

namespace App\UserModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use App\CommonModule\Presenters\SecurePresenter;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Security\AuthenticationException;
use App\UserModule\Model\UserAuthenticator;

final class AuthenticationPresenter extends BasePresenter
{
    private $loginControlFactory;
    public function __construct(\App\UserModule\Controls\Login\ILoginControlFactory $loginControlFactory)
    {
        parent::__construct();
        $this->loginControlFactory = $loginControlFactory;
    }
    public function actionSignIn(): void
    {
        \Tracy\Debugger::log('SignIn action loaded');
        if ($this->getUser()->isLoggedIn())
        {
            $this->redirect(':CommonModule:Home:default');
        }
    }

    public function handleLogout(): void
    {
        $this->getUser()->logout(true);
        $this->flashMessage('You have been logged out.', 'info');
        $this->redirect(':CommonModule:Home:default');
    }
    // Creates Sign-In Form
    protected function createComponentSignInForm(): \App\UserModule\Controls\Login\LoginControl
    {
        return $this->loginControlFactory->create();
    }

    public function signInFormSucceeded(Form $form, \stdClass $values): void
    {
        try
        {
            $this->getUser()->login($values->username, $values->password);

            $session = $this->getSession('user_activity');
            $session->lastActivity = time();
            \Tracy\Debugger::log('Auth. User activity recorded at: ' . date('Y-m-d H:i:s', $session->lastActivity), \Tracy\ILogger::INFO);

            $this->flashMessage('Login successful!');
            $this->redirect('Homepage:default');
        }
        catch (AuthenticationException $e)
        {
            $form->addError('Incorrect username or password.');
        }
    }
}