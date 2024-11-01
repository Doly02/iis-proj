<?php

declare(strict_types=1);

namespace App\ConferenceModule\Model;

use Nette\Application\UI\Form;
use App\RoomModule\Model\RoomService;
use Nette\Utils\Validators;
use Tracy\OutputDebugger;


final class ConferenceFormFactory
{
    private RoomService $roomService;
    public function __construct(RoomService $roomService)
    {
        $this->roomService = $roomService;
    }

    public function createConferenceForm() : Form
    {
        $form = new Form;

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

        // Fetch rooms from the database
        $rooms = $this->roomService->fetchAll(); // Adjust this line to match your database access method
        $roomOptions = [];
        foreach ($rooms as $room) {
            $roomOptions[$room->id] = $room->name; // Assuming 'id' and 'name' are the column names
        }

        // Multi-select for room selection
        $form->addMultiSelect('rooms', 'Select Rooms:', $roomOptions)
            ->setRequired('Please select at least one room.')
            ->setHtmlAttribute('class', 'form-control');

        $form->addSubmit('send', 'Add Conference')
            ->setHtmlAttribute('class', 'btn btn-primary');

        // Validating time
        $form->onValidate[] = function (Form $form) {
            $values = $form->getValues();

            // convert DateTimeImmutable to a string
            $startDateTime = $values->start_time;
            $endDateTime = $values->end_time;

            // Check if start time is not before end time
            if ($startDateTime >= $endDateTime) {
                $form->addError('The end time must be after the start time.');
            }
        };

        return $form;
    }

}