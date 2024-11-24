<?php

declare(strict_types=1);

namespace App\ConferenceModule\Controls\AddRoomToConference;

use App\ConferenceHasRoomsModule\Model\ConferenceHasRoomsService;
use App\ConferenceModule\Model\ConferenceService;
use App\RoomModule\Model\RoomService;
use App\TicketModule\Model\TicketService;
use App\ConferenceModule\Model\ConferenceFormFactory;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Application\UI\Control;
use Tracy\Debugger;

final class AddRoomToConferenceControl extends Control
{
    private $user;
    private ConferenceService $conferenceService;
    private RoomService $roomService;
    private ConferenceHasRoomsService $conferenceHasRoomsService;
    private TicketService $ticketService;
    private ConferenceFormFactory $conferenceFormFactory;
    private int $conferenceId;
    private $conference;



    public function __construct(\Nette\Security\User $user, ConferenceService $conferenceService,
                                RoomService $roomService, ConferenceHasRoomsService $conferenceHasRoomsService,
                                TicketService $ticketService,
                                ConferenceFormFactory $conferenceFormFactory)
    {
        $this->user = $user;
        $this->conferenceService = $conferenceService;
        $this->roomService = $roomService;
        $this->conferenceHasRoomsService = $conferenceHasRoomsService;
        $this->ticketService = $ticketService;
        $this->conferenceFormFactory = $conferenceFormFactory;
    }

    public function render(): void
    {
        $this->template->conferenceId = $this->conferenceId;
        $this->conference = $this->conferenceService->getConferenceById($this->conferenceId);
        $this->template->conference = $this->conference;

        $this->template->setFile(__DIR__ . '/../../templates/AddRoomToConference/add.latte');
        $this->template->render();
    }

    public function setConferenceId(int $conferenceId): void
    {
        $this->conferenceId = $conferenceId;
        \Tracy\Debugger::barDump($conferenceId, 'Conference ID in Control');
    }

    // Form for adding rooms
    protected function createComponentAddRoomToConferenceForm() : \Nette\Application\UI\Form
    {
        $form = new Form();
        $conference = $this->conferenceService->getConferenceById($this->conferenceId);
        // null check
        if(!$conference) return $form;

        // Allowed times are from conference start to its end
        $allowedStartTime = new \DateTimeImmutable($conference->start_time->format('Y-m-d H:i:s'));
        $allowedEndTime = new \DateTimeImmutable($conference->end_time->format('Y-m-d H:i:s'));
        \Tracy\Debugger::barDump($allowedStartTime, 'Start time');
        \Tracy\Debugger::barDump($allowedEndTime, 'End time');
        $form = $this->conferenceFormFactory->createAddRoomsToConferenceForm($allowedStartTime, $allowedEndTime);
        $form->onSuccess[] = [$this, 'addConferenceFormSucceeded'];

        return $form;
    }

    public function addConferenceFormSucceeded(Form $form, \stdClass $values): void
    {
        $err = 0;
        $presenter = $this->getPresenter();
        \Tracy\Debugger::barDump($values, 'Form Data');

        try {
            $conference = $this->conferenceService->getConferenceById($this->conferenceId);
            $oldCapacity = $conference->capacity;
            $newCapacity = $oldCapacity;
            foreach ($values->rooms as $roomId) {
                // Sum new capacity
                $room = $this->roomService->fetchById($roomId);
                $newCapacity += $room->capacity;

                // Add room to conference
                $this->conferenceHasRoomsService->addConferenceHasRooms([
                    'room_id' => $roomId,
                    'conference_id' => $this->conferenceId,
                    'booking_start' => $conference->start_time,
                    'booking_end' => $conference->end_time,
                ]);
            }

            // Update capacity
            $this->conferenceService->updateConferenceCapacity($this->conferenceId, $newCapacity);
            // Generate tickets
            $ticketCount = $newCapacity - $oldCapacity;
            $this->ticketService->generateTickets($this->conferenceId, $ticketCount, $conference->price);

        } catch (\Exception $e) {
            $err = 1;
            $form->addError('An error occurred while adding rooms to the conference: ' . $e->getMessage());
        }

        if (null !== $presenter && $err !== 1) {
            $this->flashMessage('Rooms added to conference successfully.', 'success');
            $presenter->redirect(':ConferenceModule:ConferenceCreatorDetail:defaultOrganizer', ['id' => $this->conferenceId]);
        }

    }
}


