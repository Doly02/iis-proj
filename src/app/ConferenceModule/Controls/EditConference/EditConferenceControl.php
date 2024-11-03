<?php

declare(strict_types=1);

namespace App\ConferenceModule\Controls\EditConference;

use App\ConferenceHasRoomsModule\Model\ConferenceHasRoomsService;
use App\ConferenceModule\Model\ConferenceService;
use App\RoomModule\Model\RoomService;
use App\ConferenceModule\Model\ConferenceFormFactory;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Application\UI\Control;

final class EditConferenceControl extends Control
{
    private $user;
    private ConferenceService $conferenceService;
    private RoomService $roomService;
    private ConferenceHasRoomsService $conferenceHasRoomsService;
    private ConferenceFormFactory $conferenceFormFactory;
    private int $conferenceId;

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
        $this->template->setFile(__DIR__ . '/../../templates/EditConference/edit.latte');
        $this->template->render();
    }

    public function setConferenceId(int $conferenceId): void
    {
        $this->conferenceId = $conferenceId;
        \Tracy\Debugger::barDump($conferenceId, 'Conference ID in Control');
    }

    // Creates Add Conference Form
    protected function createComponentEditConferenceForm() : \Nette\Application\UI\Form
    {
        $form = $this->conferenceFormFactory->createConferenceForm($this->conferenceId);
        $form->onSuccess[] = [$this, 'editConferenceFormSucceeded'];
        return $form;
    }

    public function editConferenceFormSucceeded(Form $form, \stdClass $values): void
    {
        $err = 0;
        $presenter = $this->getPresenter();
        $conferenceId = (int) $values->conferenceId;
        \Tracy\Debugger::barDump($conferenceId, 'Conference ID in Success Handler');

        try {
            // Insert the conference data into the database
            $this->conferenceService->updateConference($conferenceId, [
                'name' => $values->name,
                'description' => $values->description,
                'start_time' => $values->start_time,
                'end_time' => $values->end_time,
                'price' => $values->price,
            ]);

        } catch (\Exception $e) {
            $err = 1;
            $form->addError('An error occurred while adding the conference: ' . $e->getMessage());
        }
        // TODO commit changes after successful add conference

        if (null !== $presenter && $err !== 1)
        {
            $this->flashMessage('Conference added successfully.', 'success');
            $presenter->redirect(':ConferenceModule:ConferenceList:list');
        }

    }
}
