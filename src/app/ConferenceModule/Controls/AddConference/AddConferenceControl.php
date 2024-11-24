<?php

declare(strict_types=1);

namespace App\ConferenceModule\Controls\AddConference;

use App\ConferenceHasRoomsModule\Model\ConferenceHasRoomsService;
use App\ConferenceModule\Model\ConferenceService;
use App\RoomModule\Model\RoomService;
use App\ConferenceModule\Model\ConferenceFormFactory;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Application\UI\Control;
use Nette\Security\User;

final class AddConferenceControl extends Control
{
    private $user;
    private ConferenceService $conferenceService;
    private RoomService $roomService;
    private ConferenceHasRoomsService $conferenceHasRoomsService;
    private ConferenceFormFactory $conferenceFormFactory;


    public function __construct(User $user, ConferenceService $conferenceService,
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
        $this->template->setFile(__DIR__ . '/../../templates/ConferenceAdd/add.latte');
        $this->template->render();
    }

    // Creates Add Conference Form
    protected function createComponentAddConferenceForm() : \Nette\Application\UI\Form
    {
        $form = $this->conferenceFormFactory->createConferenceForm();
        $form->onSuccess[] = [$this, 'addConferenceFormSucceeded'];
        return $form;
    }

    public function addConferenceFormSucceeded(Form $form, \stdClass $values): void
    {
        $err = 0;
        $presenter = $this->getPresenter();
        $conferenceId = null;
        $userId = $this->user->getId();
        try {
            // Insert the conference data into the database
            $conferenceId = $this->conferenceService->addConference([
                'name' => $values->name,
                'description' => $values->description,
                'area_of_interest' => $values->area_of_interest,
                'start_time' => $values->start_time,
                'end_time' => $values->end_time,
                'price' => $values->price,
                'capacity' => 0,
                'organiser_id' => $userId,
            ])->id;

        } catch (\Exception $e) {
            $err = 1;
            $form->addError('An error occurred while adding the conference: ' . $e->getMessage());
        }
        // TODO commit changes after successful add of both conference and conferenceHasRooms

        if (null !== $presenter && $err !== 1)
        {
            $this->flashMessage('Conference added successfully.', 'success');
            $presenter->redirect(':ConferenceModule:AddRoomToConference:add', ['id' => $conferenceId]);
        }

    }
}
