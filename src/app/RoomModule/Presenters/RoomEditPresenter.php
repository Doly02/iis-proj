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
    private ?int $roomId = null; // Uložené ID místnosti

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

        $this->roomId = $id;

        // Validation
        if ($id <= 0) {
            throw new BadRequestException('Invalid room ID.');
        }

        // Load Room From DB
        $this->room = $this->_roomService->getTable()->get($id);
        \Tracy\Debugger::barDump($this->room, 'Room Fetched in actionEdit');

        if (!$this->room) {
            throw new BadRequestException('Room not found.');
        }
    }

    protected function createComponentEditRoom(): EditRoomControl
    {
        // Load Room ID From URL or POST data
        $id = $this->getParameter('id');

        if ($id === null) {
            // If Post Data, Load Value From Form
            $post = $this->getHttpRequest()->getPost();
            if (isset($post['id'])) {
                $id = (int)$post['id'];
            }
        }

        if (!$this->room && $id) {
            $this->room = $this->_roomService->getTable()->get($id);
            \Tracy\Debugger::barDump($this->room, 'Room Fetched in createComponentEditRoom');
        }

        if (!$this->room) {
            throw new BadRequestException('Room not found.');
        }

        // Create Component EditRoomControl With Actual ID
        return $this->editRoomControlFactory->create($this->room->id);
    }

    public function renderEdit(): void
    {
        $this->template->room = $this->room;
    }
}