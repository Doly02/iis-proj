<?php

namespace App\ConferenceModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use App\ConferenceModule\Controls\AddRoomToConference;
use App\ConferenceModule\Model\ConferenceService;
use Tracy\Debugger;

final class AddRoomToConferencePresenter extends BasePresenter
{
    private $addRoomToConferenceControlFactory;

    private int $conferenceId;
    private ConferenceService $conferenceService;

    public function __construct(AddRoomToConference\IAddRoomToConferenceControlFactory $addRoomToConferenceControlFactory, ConferenceService $conferenceService)
    {
        parent::__construct();
        $this->addRoomToConferenceControlFactory = $addRoomToConferenceControlFactory;
        $this->conferenceService = $conferenceService;
    }

    public function actionAdd(int $id): void
    {
        /* Load Data And Prepare Before Render Of Template */
        \Tracy\Debugger::log('Reached add in presenter.');
        \Tracy\Debugger::barDump($id, 'Conference ID');
        Debugger::log('Reached add rooms with ID: ' . $id, 'info');
        // this is a trap and does not set anything, but I guess it's nice to reach it like this
        // maybe could be useful for setting class vars instead of $this->getParameter();
        $this->template->conferenceId = $id;

        $this->template->conference = $this->conferenceService->getConferenceById($id);
        \Tracy\Debugger::barDump($this->template->conference, 'Conference data');
    }

    protected function createComponentAddRoomToConferenceForm(): AddRoomToConference\AddRoomToConferenceControl
    {
        $control = $this->addRoomToConferenceControlFactory->create();

        $conferenceId = (int) $this->getParameter('id');
        $control->setConferenceId($conferenceId);

        return $control;
    }
}