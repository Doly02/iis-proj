<?php

declare(strict_types=1);


namespace App\UserModule\Controls\CreateAdmin;

use App\UserModule\Enums\Action;
use App\UserModule\Enums\Role;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;
use App\UserModule\Model\AuthenticationFactory;
use App\UserModule\Model\UserService;
use App\UserModule\Model\UserFormFactory;
use App\CommonModule\Presenters\SecurePresenter;
use Exception;

final class CreateAdminControl extends Control
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

    protected function createComponentCreateAdminForm() : Form
    {
        $form = $this->_userFormFactory->createAdminForm();
        $form->onSuccess[] = [$this, 'onSuccessCreateAdminForm'];
        return $form;
    }

    public function onSuccessCreateAdminForm(Form $form, ArrayHash $vals): void
    {
        $presenter = $this->getPresenter();

        if ($presenter instanceof SecurePresenter)
        {
            $presenter->checkPrivilege(null, AuthenticationFactory::ACTION_ADD);
        }
        try
        {
            $this->_userService->registrateUser($vals,Role::ADMIN);
        }
        catch (Exception $e)
        {
            $form->addError($e->getMessage());
            return;
        }

        if (null !== $presenter)
        {
            $presenter->flashMessage('Registration Successfully Performed!', 'success');
            $presenter->redirect(':CommonModule:Home:default');
        }
    }

    public function render() : void
    {
        $this->template->setFile(__DIR__ . '/../../templates/CreateAdmin/default.latte');
        $this->template->render();
    }
}