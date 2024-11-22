<?php

declare(strict_types=1);

namespace App\UserModule\Controls\Login;

use Tracy\Debugger;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use App\UserModule\Model\UserService;
use Nette\Application\UI\Control;
use Nette\Security\User;
use Nette\Utils\Validators;
use Others\SSL\SslOperations;

final class LoginControl extends Control
{
    /** @var User */
    private $user;

    private $_userService;
    public $backLink = '';

    public function __construct(User $user,
    UserService $userService)
    {
        $this->user = $user;
        $this->_userService = $userService;
    }
    protected function createComponentSignInForm() : Form
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
            $this->user->login($values->email, $values->password);

            $userId = $this->user->getId();
            $userData = $this->_userService->getUserDataAsArray($userId);
            $ssl = new SslOperations();

            if ($userData)
            {
                $accountType = $userData['account_type'];
                $encryptedAccountType = $ssl->encryptAccountType($accountType);

                $httpResponse = $this->getPresenter()->getHttpResponse();
                $httpResponse->setCookie('mode', $encryptedAccountType, '7 days');
            }

            $session = $this->getPresenter()->getSession('user_activity');
            $session->lastActivity = time();

            \Tracy\Debugger::log('User activity recorded at: ' . date('Y-m-d H:i:s', $session->lastActivity), \Tracy\ILogger::INFO);

            $this->flashMessage('Login successful!', 'success');

            if ($this->backLink) {
                $this->getPresenter()->restoreRequest($this->backLink);
            }

            $this->getPresenter()->redirect(':ConferenceModule:ConferenceList:list');
        }
        catch (AuthenticationException $e)
        {
            // Pokud dojde k chybě autentizace, přidáme chybu do formuláře
            $form->addError('Login failed: ' . $e->getMessage());
        }
    }
}
