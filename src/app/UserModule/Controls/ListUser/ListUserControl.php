<?php

declare(strict_types=1);

namespace App\UserModule\Controls\ListUser;

use App\CommonModule\Controls\DataGrid\IDataGridControlFactory;
use App\ConferenceModule\Model\ConferenceService;
use App\ReservationModule\Model\ReservationService;
use App\UserModule\Model\UserService;
use Nette\Application\UI\Control;
use Nette\Database\Table\Selection;
use Nette\Security\User;
use Ublaboo\DataGrid\Column\Action\Confirmation\StringConfirmation;
use Ublaboo\DataGrid\DataGrid;
use Ublaboo\DataGrid\Exception\DataGridException;

final class ListUserControl extends Control
{
    private UserService $_userService;
    private User $_user;
    private IDataGridControlFactory $_dataGridControlFactory;

    public function __construct(
        User               $user,
        UserService  $userService,
        IDataGridControlFactory $dataGridControlFactory
    )
    {
        $this->_user = $user;
        $this->_userService = $userService;
        $this->_dataGridControlFactory = $dataGridControlFactory;
    }

    protected function createComponentUserList(): DataGrid
    {
        $grid = $this->_dataGridControlFactory->create($this->_userService);
        $grid->setDataSource($this->_userService->getAllUsers());
        $grid->setPagination(false);

        $grid->addColumnText('name', 'First Name')
            ->setSortable();

        $grid->addColumnText('surname', 'Last Name')
            ->setSortable();

        $grid->addColumnText('email', 'Email')
            ->setSortable()
            ->setFilterText();

        $grid->addColumnText('account_type', 'Account Type')
            ->setSortable()
            ->setFilterSelect([
                'admn' => 'Admin',
                'user' => 'User',
            ]);

        $grid->addAction('edit', 'Edit', ':UserModule:User:editUser', ['userId' => 'id'])
            ->setIcon('edit')
            ->setClass('btn btn-primary btn-inline')
            ->setTitle('Edit User');

        $grid->addAction('delete', 'Delete', ':UserModule:UserList:delete')
            ->setIcon('trash')
            ->setClass('btn btn-danger btn-inline')
            ->setConfirmation(
                new StringConfirmation('Are you sure you want to delete the user "%s"?', 'email')
            );

        return $grid;
    }

    public function handleDelete(int $userId): void
    {
        $user = $this->_userService->getUserById($userId);
        if ($user) {
            $user->delete();
            $this->flashMessage('User was successfully deleted.', 'success');
        } else {
            $this->flashMessage('User not found.', 'error');
        }
        $this->redirect('this');
    }

    public function render() : void
    {
        $this->template->setFile(__DIR__ . '/../../templates/UserList/list.latte');
        $this->template->render();
    }
}