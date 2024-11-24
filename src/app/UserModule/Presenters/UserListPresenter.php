<?php

namespace App\UserModule\Presenters;

use App\CommonModule\Presenters\SecurePresenter;
use App\UserModule\Controls\ListUser\IListUserControlFactory;
use App\UserModule\Controls\ListUser\ListUserControl;
use App\UserModule\Model\UserService;
use Nette\Security\User;

final class UserListPresenter extends SecurePresenter
{
    private IListUserControlFactory $_listUserControlFactory;
    private User $_user;
    private UserService $_userService;

    public function __construct(
        IListUserControlFactory $listUserControlFactory,
        User $user,
        UserService $userService
    )
    {
        parent::__construct();
        $this->_listUserControlFactory = $listUserControlFactory;
        $this->_user = $user;
        $this->_userService = $userService;
    }

    protected function createComponentUserList() : ListUserControl
    {
        /* Check The User And Permissions */
        $userId = $this->user->getId();
        if (!$userId) {
            $this->flashMessage('User is not logged in.', 'error');
            $this->redirect(':UserModule:Auth:signIn');
        }
        if ($this->template->accountType !== 'admn') {
            $this->flashMessage('You do not have permission.', 'error');
            $this->redirect(':ConferenceModule:ConferenceList:list');
        }

        return $this->_listUserControlFactory->create();
    }

    public function handleDelete(int $userId): void
    {
        $user = $this->_userService->getUserById($userId);
        if ($user)
        {
            $user->delete();
            $this->flashMessage('User was successfully deleted.', 'success');
        }
        else
        {
            $this->flashMessage('User not found.', 'error');
        }
        $this->redirect('this');
    }
    public function renderDefault(): void
    {
        // Add any setup required for rendering the default view.
    }
}