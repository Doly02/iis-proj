<?php

declare(strict_types=1);

namespace App\ConferenceModule\Model;

use App\ConferenceModule\Model\ConferenceService;
use Nette\Application\UI\Form;
use App\RoomModule\Model\RoomService;
use Nette\Utils\Validators;
use Tracy\OutputDebugger;


final class ConferenceFormFactory
{
    private ConferenceService $conferenceService;
    private RoomService $roomService;
    public function __construct(ConferenceService $conferenceService, RoomService $roomService)
    {
        $this->conferenceService = $conferenceService;
        $this->roomService = $roomService;
    }

    public function createConferenceForm(int $conferenceId = null) : Form
    {
        $form = new Form;

        $conference = null;
        if ($conferenceId !== null) {
            $conference = $this->conferenceService->fetchById($conferenceId); // Replace with your method to get conference data by ID
        }
        // ID seems to reset after send
        $form->addHidden('conferenceId', $conferenceId);


        $form->addText('name', 'Name:')
            ->setRequired('Enter the conference name.')
            ->addRule($form::MaxLength, 'Name can be at most 30 characters long.', 30)
            ->setHtmlAttribute('class', 'form-control');

        $form->addTextArea('description', 'Description:')
            ->setRequired('Enter a description for the conference.')
            ->setHtmlAttribute('class', 'form-control');

        // User-friendly selection of date and time
        $form->addDateTime('start_time', 'Conference start:')
            ->setRequired('Enter the start date.')
            ->setHtmlAttribute('class', 'form-control');

        $form->addDateTime('end_time', 'Conference end:')
            ->setRequired('Enter the end date.')
            ->setHtmlAttribute('class', 'form-control');

        $form->addText('price', 'Price:')
            ->setRequired('Enter the price.')
            ->addRule($form::Float, 'Price must be a number.')
            ->setHtmlAttribute('class', 'form-control');

        $form->addSubmit('send', 'Send')
            ->setHtmlAttribute('class', 'btn btn-primary');

        // Validating time
        $form->onValidate[] = function (Form $form) {
            $values = $form->getValues();

            // convert DateTimeImmutable to a string
            $startDateTime = $values->start_time;
            $endDateTime = $values->end_time;

            $price = $values->price;

            // Check if start time is not before end time
            if ($startDateTime >= $endDateTime) {
                $form->addError('The end time must be after the start time.');
            }

            // Check price
            if ($price <= 0) {
                $form->addError('Price cannot be negative.');
            }
        };

        // for edit
        if ($conference) {
            $form->setDefaults([
                'name' => $conference->name,
                'description' => $conference->description,
                'start_time' => $conference->start_time,
                'end_time' => $conference->end_time,
                'price' => $conference->price,
            ]);
        }

        return $form;
    }

    public function createAddRoomsToConferenceForm(\DateTimeImmutable $allowedStartTime, \DateTimeImmutable $allowedEndTime) : Form
    {
        $form = new Form;

        $availableRooms = $this->roomService->fetchAvailableRooms($allowedStartTime, $allowedEndTime);

        $roomOptions = [];
        foreach ($availableRooms as $room) {
            $roomOptions[$room->id] = $room->name;
        }

        // Add multiselect field to the form
        $form->addMultiSelect('rooms', 'Select Room:', $roomOptions)
            ->setRequired('Please select at least one room.');

        $form->addSubmit('send', 'Book Room')
            ->setHtmlAttribute('class', 'btn btn-primary');



        return $form;
    }

}