<?php

namespace App\UserModule\Presenters;

use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Security\AuthenticationException;
use App\UserModule\Model\FakeAuthenticator;

final class AuthenticationPresenter extends Presenter
{
    public function actionOut(): void
    {
        /* Logout of Client */
        $this->getUser()->logout();
        $this->flashMessage('You have been signed out.');
        $this->redirect('Homepage:');
    }

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
        try
        {
            $this->getUser()->login($values->username, $values->password);
            $this->flashMessage('Login successful!');
            $this->redirect('Homepage:');
        }
        catch (AuthenticationException $e)
        {
            $form->addError('Incorrect username or password.');
        }
    }
}
