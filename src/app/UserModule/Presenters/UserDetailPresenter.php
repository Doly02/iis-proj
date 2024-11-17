<?php

namespace App\UserModule\Presenters;

use App\CommonModule\Presenters\SecurePresenter;
use App\UserModule\Model\UserService;

final class UserDetailPresenter extends SecurePresenter
{
    private $_userService;

    public function __construct(UserService $userService)
    {
        parent::__construct();
        $this->_userService = $userService;
    }

    public function renderDefault(): void
    {
        $userId = $this->getUser()->getId();

        if (!$userId) {
            $this->flashMessage('You need to be logged in to view this page.', 'error');
            $this->redirect(':UserModule:Auth:signIn');
        }

        $userRow = $this->_userService->getUserById($userId);

        if (!$userRow) {
            $this->flashMessage('User not found.', 'error');
            $this->redirect('Homepage:default');
        }

        $userData = (object) [
            'name' => $userRow->name,
            'email' => $userRow->email,
            'surname' => $userRow->surname
        ];

        $this->template->userData = $userData;
    }

}