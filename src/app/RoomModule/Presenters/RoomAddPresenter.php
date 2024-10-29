<?php

namespace App\RoomModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use App\RoomModule\Model\RoomService;
use Nette\Application\UI\Form;
use Nette\Application\AbortException;
use Nette\Database\Table\ActiveRow;

final class RoomAddPresenter extends BasePresenter
{
    private RoomService $roomService;

    public function __construct(RoomService $roomService)
    {
        parent::__construct();
        $this->roomService = $roomService;
    }

    protected function createComponentAddRoomForm(): Form
    {
        $form = new Form;

        $form->addText('name', 'Name:')
            ->setRequired('Enter the room name.')
            ->addRule($form::MaxLength, 'Name can be at most 30 characters long.', 30);

        $form->addInteger('capacity', 'Capacity:');

        $form->addSubmit('send', 'Add');

        $form->onSuccess[] = [$this, 'addRoomFormSucceeded'];
        return $form;
    }

    public function addRoomFormSucceeded(Form $form, \stdClass $values): void
    {
        try {
            // Call RoomService to insert room data
            $newRoom = $this->roomService->addRoom([
                'name' => $values->name,
                'capacity' => $values->capacity,
                'creator_id' => 1, // TODO: Replace with actual user ID
            ]);

            if ($newRoom instanceof ActiveRow) {
                $this->flashMessage('Room added successfully.', 'success');
            }

            $this->redirect(':CommonModule:Home:default');

        } catch (AbortException $e) {
            // Allow AbortException silently (no action needed)
        } catch (\Exception $e) {
            $form->addError('An error occurred while adding the room: ' . $e->getMessage());
        }
    }
}