<?php

namespace App\RoomModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use Nette\Application\UI\Form;
use Nette\Application\AbortException;

final class RoomAddPresenter extends BasePresenter
{

    // Creates Add Conference Form
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
            // Insert the room data into the database
            $this->database->table('rooms')->insert([
                'name' => $values->name,
                'capacity' => $values->capacity,
                'creator_id' => 1, // TODO: User id
            ]);

            $this->flashMessage('Room added successfully.', 'success');

            $this->redirect(destination: ':CommonModule:Home:default'); // not working


        } catch (AbortException $e) {
            // Allow AbortException silently (no action needed)
        } catch (\Exception $e) {
            $form->addError('An error occurred while adding the room: ' . $e->getMessage());
        }
    }
}