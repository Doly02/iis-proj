<?php

declare(strict_types=1);

namespace App\ConferenceModule\Controls\AddRoomToConference;

use App\ConferenceHasRoomsModule\Model\ConferenceHasRoomsService;
use App\ConferenceModule\Model\ConferenceService;
use App\RoomModule\Model\RoomService;
use App\ConferenceModule\Model\ConferenceFormFactory;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Application\UI\Control;

final class AddRoomToConferenceControl extends Control
{
    private $user;
    private ConferenceService $conferenceService;
    private RoomService $roomService;
    private ConferenceHasRoomsService $conferenceHasRoomsService;
    private ConferenceFormFactory $conferenceFormFactory;


    public function __construct(\Nette\Security\User $user, ConferenceService $conferenceService,
                                RoomService $roomService, ConferenceHasRoomsService $conferenceHasRoomsService,
                                ConferenceFormFactory $conferenceFormFactory)
    {
        $this->user = $user;
        $this->conferenceService = $conferenceService;
        $this->roomService = $roomService;
        $this->conferenceHasRoomsService = $conferenceHasRoomsService;
        $this->conferenceFormFactory = $conferenceFormFactory;
    }

    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/../../templates/AddRoomToConference/add.latte');
        $this->template->render();
    }

    // Form for adding rooms
    protected function createComponentAddRoomToConferenceForm(\DateTimeImmutable $allowedStartTime,
                                                              \DateTimeImmutable $allowedEndTime) : \Nette\Application\UI\Form
    {
        $form = $this->conferenceFormFactory->createAddRoomsToConferenceForm($allowedStartTime, $allowedEndTime);
        $form->onSuccess[] = [$this, 'addConferenceFormSucceeded'];
        return $form;
    }

    public function addConferenceFormSucceeded(Form $form, \stdClass $values): void
    {
        $err = 0;
        $presenter = $this->getPresenter();


        $rooms = $this->roomService->fetchAvailableRooms($values->startDate, $values->endDate);

        // Sum capacity of rooms
        $totalCapacity = 0;
        if (!empty($values->rooms)) {
            foreach ($values->rooms as $roomId) {
                $room = $this->roomService->fetchById($roomId);
                if ($room) {
                    $totalCapacity += $room->capacity;
                }
            }
        }

        try {
            // update conference capacity, add rooms
        } catch (\Exception $e) {
            $err = 1;
            $form->addError('An error occurred while adding the conference: ' . $e->getMessage());
        }

        if (null !== $presenter && $err !== 1)
        {
            $this->flashMessage('Conference added successfully.', 'success');
            $presenter->redirect(':CommonModule:Home:default');
        }

    }
}


