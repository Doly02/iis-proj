<?php

namespace App\ConferenceModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use Nette\Application\UI\Form;
use Nette\Application\AbortException;

final class ConferenceAddPresenter extends BasePresenter
{

    // Creates Add Conference Form
    protected function createComponentAddConferenceForm(): Form
    {
        $form = new Form;

        $form->addText('name', 'Name:')
            ->setRequired('Enter the conference name.')
            ->addRule($form::MaxLength, 'Name can be at most 30 characters long.', 30);

        $form->addTextArea('description', 'Description:')
            ->setRequired('Enter a description for the conference.');

        // User-friendly selection of date and time
        $form->addDateTime('start_time', 'Conference start:')
            ->setRequired('Enter the start date.');

        $form->addDateTime('end_time', 'Conference end:')
            ->setRequired('Enter the end date.');

        $form->addText('price', 'Price:')
            ->setRequired('Enter the price.')
            ->addRule($form::Float, 'Price must be a number.');

        $form->addInteger('capacity', 'Capacity:')
            ->setRequired('Enter the capacity.')
            ->addRule($form::Min, 'Capacity must be at least 1.', 1);

        // Fetch rooms from the database
        $rooms = $this->database->table('rooms')->fetchAll(); // Adjust this line to match your database access method
        $roomOptions = [];
        foreach ($rooms as $room) {
            $roomOptions[$room->id] = $room->name; // Assuming 'id' and 'name' are the column names
        }

        // Add multi-select for room selection
        $form->addMultiSelect('rooms', 'Select Rooms:', $roomOptions)
            ->setRequired('Please select at least one room.');

        $form->addSubmit('send', 'Add Conference');

        $form->onSuccess[] = [$this, 'addConferenceFormSucceeded'];
        return $form;
    }

    public function addConferenceFormSucceeded(Form $form, \stdClass $values): void
    {
        try {
            // Insert the conference data into the database
            $conferenceId = $this->database->table('conferences')->insert([
                'name' => $values->name,
                'description' => $values->description,
                'start_time' => $values->start_time,
                'end_time' => $values->end_time,
                'price' => $values->price,
                'capacity' => $values->capacity, // TODO: Add together capacity of rooms
                'organiser_id' => 1, // TODO: User id
            ])->id;

            // Insert selected rooms into conference_has_rooms
            if (!empty($values->rooms)) {
                foreach ($values->rooms as $roomId) {
                    $this->database->table('conference_has_rooms')->insert([
                        'conference_id' => $conferenceId,
                        'room_id' => $roomId,
                    ]);
                }
            }

            $this->flashMessage('Conference added successfully.', 'success');

            $this->redirect(destination: ':CommonModule:Home:default'); // needs a fix


        } catch (AbortException $e) {
            // Allow AbortException silently (no action needed)
        } catch (\Exception $e) {
            $form->addError('An error occurred while adding the conference: ' . $e->getMessage());
        }
    }
}