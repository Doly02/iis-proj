<?php
namespace App\RoomModule\Controls\EditRoom;

use App\RoomModule\Model\RoomService;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Database\Table\ActiveRow;

final class EditRoomControl extends Control
{
    private RoomService $_roomService;
    private int $roomId;
    private ActiveRow $room;

    public function __construct(RoomService $roomService, int $roomId)
    {
        $this->_roomService = $roomService;
        $this->roomId = $roomId;

        $this->room = $this->_roomService->getTable()->get($roomId);

        if (!$this->room) {
            throw new \Nette\Application\BadRequestException("Room with ID {$roomId} not found.");
        }
    }

    protected function createComponentEditRoom(): Form
    {
        $form = new Form;

        $form->addText('name', 'Room Name:')
            ->setRequired('Please enter the room name.')
            ->setDefaultValue($this->room->name);

        $form->addInteger('capacity', 'Capacity:')
            ->setRequired('Please enter the room capacity.')
            ->setDefaultValue($this->room->capacity)
            ->addRule($form::MIN, 'Capacity must be at least 1', 1);

        // Hidden ID
        $form->addHidden('id', (string)$this->roomId);

        $form->addSubmit('save', 'Save Changes');
        $form->onSuccess[] = [$this, 'handleEditRoom'];

        return $form;
    }

    public function handleEditRoom(Form $form, \stdClass $values): void
    {
        $room = $this->_roomService->getTable()->get($values->id);
        if (!$room) {
            throw new \Nette\Application\BadRequestException("Room with ID {$values->id} not found.");
        }

        $this->_roomService->updateRoom($values->id, [
            'name' => $values->name,
            'capacity' => $values->capacity,
        ]);

        $this->presenter->flashMessage('Room updated successfully.', 'success');
        $this->presenter->redirect(':RoomModule:RoomList:list');
    }

    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/../../templates/RoomEdit/editRoom.latte');
        $this->template->render();
    }
}