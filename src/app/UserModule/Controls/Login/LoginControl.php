<?php

declare(strict_types=1);

namespace App\UserModule\Controls\Login;

use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Application\UI\Control;
use Nette\Security\User;
use Nette\Utils\Validators;
use App\UserModule\Model\UserAuthenticator;

final class LoginControl extends Control
{
    /** @var User */
    private $user;

    /** @var UserAuthenticator */
    private $authenticator;

    public $backLink = '';

    public function __construct(User $user, UserAuthenticator $authenticator)
    {
        $this->user = $user;
        $this->authenticator = $authenticator;
    }

    protected function createComponentSignInForm(): Form
    {
        $form = new Form;
        $form->addText('email', 'E-mail:')
            ->setRequired('Please enter your e-mail.')
            ->addRule(function ($email) {
                return Validators::isEmail($email->value);
            }, 'Please enter a valid e-mail.');

        $form->addPassword('password', 'Password:')
            ->setRequired('Please enter your password.');

        $form->addSubmit('send', 'Sign in');

        $form->onSuccess[] = [$this, 'signInFormSucceeded'];
        return $form;
    }
    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/../../templates/Authentication/signIn.latte');
        $this->template->render();
    }
    public function signInFormSucceeded(Form $form, \stdClass $values): void
    {
        try
        {
            $this->user->setAuthenticator($this->authenticator);
            $this->user->login($values->email, $values->password);

            $this->flashMessage('Login successful!', 'success');
            $this->getPresenter()->redirect(':CommonModule:Home:default');
        }
        catch (AuthenticationException $e)
        {
            // Zobrazení detailní chybové zprávy
            $form->addError('Login failed: ' . $e->getMessage());
        }
        catch (\Exception $e) // Zajištění zachycení i ostatních výjimek
        {
            $form->addError('An unexpected error occurred: ' . $e->getMessage());
        }
        /*
        catch (AuthenticationException $e)
        {
            $form->addError('Incorrect e-mail or password.');
        }*/
    }
}
