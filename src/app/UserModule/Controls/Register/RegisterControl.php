<?php

declare(strict_types=1);


namespace App\UserModule\Controls\Register;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;
use App\UserModule\Model\Action;
use App\UserModule\Model\UserService;
use Exception;

final class RegisterControl extends Control
{
    private $_userService;

    private $_userFormFactory;

    public function __construct(
        \App\UserModule\Model\UserService $userService,
        \App\UserModule\Model\UserFormFactory $userFormFactory,
    )
    {
        // parent::__construct();
        $this->_userService = $userService;
        $this->_userFormFactory = $userFormFactory;
    }

    protected function createComponentRegisterForm() : \Nette\Application\UI\Form
    {
        $form = $this->_userFormFactory->createUserForm();
        $form->onSuccess[] = [$this, 'onSuccessRegisterForm'];

        return $form;
    }

    public function onSuccessRegisterForm(Form $form, ArrayHash $vals): void
    {
        $presenter = $this->getPresenter();

        if ($presenter instanceof \App\CommonModule\Presenters\SecurePresenter)
        {
            $presenter->checkPrivilege(null, Action::ADD);
        }
        try
        {
            $this->_userService->registrateUser($vals);
        }
        catch (Exception $e)
        {
            $form->addError($e->getMessage());
            return;
        }

        if (null !== $presenter)
        {
            $presenter->flashMessage('Registration Successfully Performed!', 'success');
            $presenter->redirect(':UserModule:Authentication:signIn');
        }
    }

    public function render() : void
    {
        $this->template->setFile(__DIR__ . '/../../templates/Register/default.latte');
        $this->template->render();
    }
}