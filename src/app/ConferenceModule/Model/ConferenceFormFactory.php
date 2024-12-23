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

    public function createConferenceForm(int $conferenceId = null): Form
    {
        $form = new Form;

        $conference = null;
        $isReadOnly = false;

        if ($conferenceId !== null) {
            $conference = $this->conferenceService->fetchById($conferenceId);

            // Check if conference has reservations, rooms, or lectures
            $hasReservations = $this->conferenceService->getReservationsCount($conferenceId) > 0;
            $hasRooms = $this->conferenceService->getRoomsCount($conferenceId) > 0;
            $hasLectures = $this->conferenceService->getLecturesCount($conferenceId) > 0;

            // Set read-only if any of these are true
            $isReadOnly = $hasReservations || $hasRooms || $hasLectures;
        }

        $form->addHidden('conferenceId', $conferenceId);

        $form->addText('name', 'Name:')
            ->setRequired('Enter the conference name.')
            ->setHtmlAttribute('class', 'form-control');

        $form->addTextArea('description', 'Description:')
            ->setRequired('Enter a description for the conference.')
            ->setHtmlAttribute('class', 'form-control');

        $form->addTextArea('area_of_interest', 'Area of interest:')
            ->setRequired('Enter an area of interest for the conference.')
            ->setHtmlAttribute('class', 'form-control');


        if ($isReadOnly) {
            $form->addText('start_time', 'Conference start:')
                ->setHtmlAttribute('class', 'form-control')
                ->setHtmlAttribute('readonly', true);

            $form->addText('end_time', 'Conference end:')
                ->setHtmlAttribute('class', 'form-control')
                ->setHtmlAttribute('readonly', true);

            $form->addText('price', 'Price:')
                ->setHtmlAttribute('class', 'form-control')
                ->setHtmlAttribute('readonly', true);
        } else {
            // Editable fields for conferences without reservations
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
        }

        $form->addSubmit('send', 'Send')
            ->setHtmlAttribute('class', 'btn btn-primary');

        // Validating time
        $form->onValidate[] = function (Form $form) use ($hasReservations) {
            $values = $form->getValues();

            if (!$hasReservations) {
                // Validate only if fields are editable
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
            }
        };

        // Set default values for editing
        if ($conference) {
            $form->setDefaults([
                'name' => $conference->name,
                'description' => $conference->description,
                'area_of_interest' => $conference->area_of_interest,
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
        $form->addCheckboxList('rooms', 'Select Rooms:', $roomOptions)
            ->setRequired('Please select at least one room.');

        $form->addSubmit('send', 'Book Rooms')
            ->setHtmlAttribute('class', 'btn btn-primary');



        return $form;
    }

}