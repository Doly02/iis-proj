<?php

namespace App\UserModule\Presenters;

use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Security\AuthenticationException;
use App\UserModule\Model\FakeAuthenticator;

final class AuthenticationPresenter extends Presenter
{
    public function actionSignIn(): void
    {
        \Tracy\Debugger::log('SignIn action loaded');
        if ($this->getUser()->isLoggedIn()) {
            $this->redirect(':Core:Homepage:default');
        }
    }

    // Creates Sign-In Form
    protected function createComponentSignInForm(): Form
    {
        $form = new Form;
        $form->addText('username', 'Username:')
            ->setRequired('Please enter your username.');

        $form->addPassword('password', 'Password:')
            ->setRequired('Please enter your password.');

        $form->addSubmit('send', 'Sign in');

        $form->onSuccess[] = [$this, 'signInFormSucceeded'];
        return $form;
    }

    public function signInFormSucceeded(Form $form, \stdClass $values): void
    {
        try {
            $this->getUser()->login($values->username, $values->password);
            $this->flashMessage('Login successful!');
            $this->redirect('Homepage:default');
        } catch (AuthenticationException $e) {
            $form->addError('Incorrect username or password.');
        }
    }
}