<?php

declare(strict_types=1);

namespace App\RoomModule\Controls\ListRoom;

use App\CommonModule\Controls\DataGrid\DataGridControl;
use App\CommonModule\Controls\DataGrid\IDataGridControlFactory;
use Ublaboo\DataGrid\Column\Action\Confirmation\StringConfirmation;
use App\RoomModule\Model\RoomService;
use Nette\Application\UI\Control;
use Nette\Database\Table\Selection;
use Nette\Security\User;

final class ListRoomControl extends Control
{
    private RoomService $_roomService;
    private $_dataGridControlFactory;

    private $_user;
    private ?Selection $userConferences = null;

    public function __construct(
        User $user,
        RoomService $roomService,
        IDataGridControlFactory $dataGridControlFactory
    ) {
        $this->_user = $user;
        $this->_roomService = $roomService;
        $this->_dataGridControlFactory = $dataGridControlFactory;
    }

    protected function createComponentListRoom(): DataGridControl
    {
        $grid = $this->_dataGridControlFactory->create($this->_roomService);

        $grid->setPagination(false);
        $grid->addColumnText('name', 'Room Name:')
            ->setSortable()
            ->setFilterText();

        $grid->addColumnText('capacity', 'Capacity:')
            ->setSortable()
            ->setFilterRange();

        $grid->addAction('edit', '', ':RoomModule:RoomEdit:editRoom')
            ->setTitle('Edit')
            ->setClass('btn btn-primary btn-inline')
            ->setIcon('edit');

        $grid->addAction('delete', '')
            ->setTitle('Delete')
            ->setClass('btn btn-danger btn-inline')
            ->setIcon('trash')
            ->setConfirmation(
                new StringConfirmation('Do you really want to delete the room "%s"?', 'name')
            )
            ->setRenderer(function ($row) {
                return '<a href="' . $this->link('delete!', ['id' => $row->id]) . '" class="btn btn-danger">Delete</a>';
            });

        return $grid;
    }

    public function handleDelete(int $id): void
    {
        if ($this->_roomService->deleteRoomById($id)) {
            $this->flashMessage('Room Was Successfully Removed!', 'success');
        } else {
            $this->flashMessage('Room Not Found.', 'error');
        }
        $this->redirect('this');
    }
    public function render() : void
    {
        $this->template->setFile(__DIR__ . '/../../templates/RoomList/list.latte');
        $this->template->render();
    }
}
