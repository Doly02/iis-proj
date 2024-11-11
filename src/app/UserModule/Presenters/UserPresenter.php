<?php

namespace App\UserModule\Presenters;

use App\CommonModule\Presenters\SecurePresenter;
use App\UserModule\Controls\EditUser\IEditUserControlFactory;
use App\UserModule\Model\UserService;
use Nette\Application\UI\Control;
use Nette\Database\Table\ActiveRow;
use Tracy\Debugger;

final class UserPresenter extends SecurePresenter
{
    private $_editUserControlFactory;
    private $_userService;

    public function __construct(
        IEditUserControlFactory $editUserControlFactory,
        UserService $userService
        )
    {
        parent::__construct();
        $this->_editUserControlFactory = $editUserControlFactory;
        $this->_userService = $userService;
    }
    public function actionList(): void
    {
        $this->checkPrivilege();
    }
    public function actionEdit(int $userId): void
    {
        $this->checkPrivilege();

        Debugger::log("Editing user with ID: $userId", Debugger::INFO);
        $user = $this->_userService->getUserById($userId);
        if (!$user instanceof ActiveRow) {
            $this->flashMessage('User not found.', 'error');
            $this->redirect(':CommonModule:Home:default');
        }
        Debugger::barDump($user, 'User Information');
        $this->template->userData = $user;
    }

    protected function createComponentEditUser(): Control
    {
        $userId = $this->getParameter('userId');
        Debugger::log("Creating EditUser component for user ID: $userId", Debugger::INFO);
        $user = $this->_userService->getUserById($userId);

        if (!$user instanceof ActiveRow)
        {
            Debugger::log("User with ID $userId not found.", Debugger::ERROR);
            throw new \Nette\Application\BadRequestException('User not found');
        }

        return $this->_editUserControlFactory->create($user);
    }
}