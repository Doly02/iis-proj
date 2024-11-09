<?php
namespace App\RoomModule\Controls\EditRoom;

use App\RoomModule\Model\RoomService;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Database\Table\ActiveRow;

final class EditRoomControl extends Control
{
    private RoomService $_roomService;
    private ?int $roomId = null;
    private ?ActiveRow $room = null;

    public function __construct(RoomService $roomService)
    {
        $this->_roomService = $roomService;
    }

    public function setRoomId(int $roomId): void
    {
        $this->roomId = $roomId;
    }

    protected function createComponentEditRoom(): Form
    {
        if ($this->roomId !== null) {
            $this->room = $this->_roomService->getTable()->get($this->roomId);
            if (!$this->room) {
                $this->presenter->flashMessage('Room not found.', 'error');
                $this->presenter->redirect('RoomList:default');
            }
        }

        $form = new Form;
        $form->addText('name', 'Room Name:')
            ->setRequired('Please enter the room name.')
            ->setDefaultValue($this->room ? $this->room->name : '');

        $form->addInteger('capacity', 'Capacity:')
            ->setRequired('Please enter the room capacity.')
            ->setDefaultValue($this->room ? $this->room->capacity : '')
            ->addRule($form::MIN, 'Capacity must be at least 1', 1);

        $form->addSubmit('save', 'Save Changes');
        $form->onSuccess[] = [$this, 'handleEditRoom'];
        return $form;
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

    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/../../templates/RoomEdit//editRoom.latte');
        $this->template->render();
    }
}
