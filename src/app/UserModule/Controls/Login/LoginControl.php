<?php

declare(strict_types=1);

namespace App\UserModule\Controls\Login;

use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Application\UI\Control;

final class LoginControl extends Control
{
    public function __construct(\Nette\Security\User $user)
    {
        $this->user = $user;
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
