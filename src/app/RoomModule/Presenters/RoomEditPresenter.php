<?php

namespace App\RoomModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use App\CommonModule\Presenters\SecurePresenter;
use App\RoomModule\Controls\EditRoom\EditRoomControl;
use App\RoomModule\Controls\EditRoom\IEditRoomControlFactory;
use App\RoomModule\Model\RoomService;
use Nette\Database\Table\ActiveRow;
use Nette\Application\BadRequestException;

final class RoomEditPresenter extends SecurePresenter
{
    private RoomService $_roomService;
    private IEditRoomControlFactory $editRoomControlFactory;
    private ?ActiveRow $room = null;

    public function __construct(
        RoomService $roomService,
        IEditRoomControlFactory $editRoomControlFactory
    ) {
        parent::__construct();
        $this->_roomService = $roomService;
        $this->editRoomControlFactory = $editRoomControlFactory;
    }

    public function actionEdit(int $id): void
    {
        $this->room = $this->_roomService->getTable()->get($id);

        if (!$this->room) {
            throw new BadRequestException('Room not found.');
        }
    }

    protected function createComponentEditRoom(): EditRoomControl
    {
        $control = $this->editRoomControlFactory->create();
        if ($this->room) {
            $control->setRoomId($this->room->id);
        }
        return $control;
    }

    public function handleEditRoom(Form $form, \stdClass $values): void
    {
        if ($this->roomId === null || !$this->room) {
            $this->presenter->flashMessage('Room not found.', 'error');
            $this->presenter->redirect('this');
            return;
        }

        $this->_roomService->updateRoom($this->roomId, [
            'name' => $values->name,
            'capacity' => $values->capacity,
        ]);

        $this->presenter->flashMessage('Room updated successfully.', 'success');
        $this->presenter->redirect('RoomList:default');
    }


    public function renderEdit(): void
    {
        $this->template->room = $this->room;
    }
}