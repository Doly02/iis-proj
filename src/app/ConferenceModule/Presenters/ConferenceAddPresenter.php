<?php

namespace App\ConferenceModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use Nette\Application\UI\Form;
use Nette\Application\AbortException;
use App\ConferenceModule\Model\ConferenceService;
use App\RoomModule\Model\RoomService;


final class ConferenceAddPresenter extends BasePresenter
{

    private ConferenceService $conferenceService;


    public function __construct(ConferenceService $conferenceService)
    {
        parent::__construct();
        $this->conferenceService = $conferenceService;
    }

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

        // Fetch rooms from the database
        $rooms = $this->conferenceService->roomService->fetchAll(); // Adjust this line to match your database access method
        $roomOptions = [];
        foreach ($rooms as $room) {
            $roomOptions[$room->id] = $room->name; // Assuming 'id' and 'name' are the column names
        }

        // Multi-select for room selection
        $form->addMultiSelect('rooms', 'Select Rooms:', $roomOptions)
            ->setRequired('Please select at least one room.');

        $form->addSubmit('send', 'Add Conference');

        // Validating time
        $form->onValidate[] = function (Form $form) {
            $values = $form->getValues();

            // Ensure to use format to convert DateTimeImmutable to a string
            $startDateTime = $values->start_time; // This is a DateTimeImmutable object
            $endDateTime = $values->end_time; // This is a DateTimeImmutable object

            // Check if start time is not before end time
            if ($startDateTime >= $endDateTime) {
                $form->addError('The end time must be after the start time.');
            }
        };


        $form->onSuccess[] = [$this, 'addConferenceFormSucceeded'];
        return $form;
    }

    public function addConferenceFormSucceeded(Form $form, \stdClass $values): void
    {
        /*
        try {
            // Sum capacity of rooms
            $totalCapacity = 0;
            if (!empty($values->rooms)) {
                foreach ($values->rooms as $roomId) {
                    $room = $this->database->table('rooms')->get($roomId);
                    if ($room) {
                        $totalCapacity += $room->capacity;
                    }
                }
            }

            // Insert the conference data into the database
            $conferenceId = $this->database->table('conferences')->insert([
                'name' => $values->name,
                'description' => $values->description,
                'start_time' => $values->start_time,
                'end_time' => $values->end_time,
                'price' => $values->price,
                'capacity' => $totalCapacity,
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
        }*/
    }
}