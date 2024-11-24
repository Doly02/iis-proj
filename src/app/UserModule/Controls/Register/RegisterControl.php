<?php

declare(strict_types=1);


namespace App\UserModule\Controls\Register;

use App\UserModule\Enums\Role;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;
use App\UserModule\Model\AuthenticationFactory;
use App\UserModule\Model\UserService;
use App\UserModule\Model\UserFormFactory;
use App\CommonModule\Presenters\SecurePresenter;
use Exception;

final class RegisterControl extends Control
{
    private $_userService;

    private $_userFormFactory;

    public function __construct(
        UserService $userService,
        UserFormFactory $userFormFactory,
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

        if ($presenter instanceof SecurePresenter)
        {
            $presenter->checkPrivilege(null, AuthenticationFactory::ACTION_ADD);
        }
        try
        {
            $this->_userService->registrateUser($vals,Role::USER);
        }
        catch (Exception $e)
        {
            $form->addError($e->getMessage());
            return;
        }

        if (null !== $presenter)
        {
            $presenter->flashMessage('Registration Successfully Performed!', 'success');
            $presenter->redirect(':ConferenceModule:ConferenceList:list');
        }
    }

    public function render() : void
    {
        $this->template->setFile(__DIR__ . '/../../templates/Register/default.latte');
        $this->template->render();
    }
}