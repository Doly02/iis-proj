<?php

namespace App\RoomModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use App\RoomModule\Model\RoomService;
use Nette\Application\UI\Form;
use Nette\Application\AbortException;
use Nette\Database\Table\ActiveRow;
use Nette\Security\User;
use Tracy\Debugger;

final class RoomAddPresenter extends BasePresenter
{
    private RoomService $roomService;
    private User $user;

    public function __construct(RoomService $roomService, User $user)
    {
        parent::__construct();
        $this->roomService = $roomService;
        $this->user = $user;
    }

    public function actionAdd(): void
    {
        if (!$this->user->isLoggedIn()) {
            $this->redirect(':CommonModule:Login:default');
        }
    }

    protected function createComponentAddRoomForm(): Form
    {
        $form = new Form;

        $form->addText('name', 'Name:')
            ->setRequired('Enter the room name.');

        $form->addInteger('capacity', 'Capacity:')
            ->setRequired('Enter the room capacity.')
            ->addRule($form::Min, 'Capacity must be greater than 0.', 1);

        $form->addSubmit('send', 'Add');

        $form->onSuccess[] = [$this, 'addRoomFormSucceeded'];
        return $form;
    }

    public function addRoomFormSucceeded(Form $form, \stdClass $values): void
    {
        $userId = $this->user->getId();
        try {
            // Call RoomService to insert room data
            $newRoom = $this->roomService->addRoom([
                'name' => $values->name,
                'capacity' => $values->capacity,
                'creator_id' => $userId,
            ]);

            if ($newRoom instanceof ActiveRow) {
                $this->flashMessage('Room added successfully.', 'success');
            }

        } catch (AbortException $e) {
            // Allow AbortException silently (no action needed)
        } catch (\Exception $e) {
            $form->addError('An error occurred while adding the room: ' . $e->getMessage());
        }
        $this->redirect(':RoomModule:RoomList:list');
    }
}