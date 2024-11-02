<?php

namespace App\ConferenceModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use App\ConferenceModule\Controls\AddRoomToConference;
use Tracy\Debugger;

final class AddRoomToConferencePresenter extends BasePresenter
{
    private $addRoomToConferenceControlFactory;

    private int $conferenceId;

    public function __construct(AddRoomToConference\IAddRoomToConferenceControlFactory $addRoomToConferenceControlFactory)
    {
        parent::__construct();
        $this->addRoomToConferenceControlFactory = $addRoomToConferenceControlFactory;
    }

    public function actionAdd(int $id): void
    {
        /* Load Data And Prepare Before Render Of Template */
        \Tracy\Debugger::log('Reached add in presenter.');
        \Tracy\Debugger::barDump($id, 'Conference ID');
        Debugger::log('Reached add rooms with ID: ' . $id, 'info');

        $this->template->conferenceId = $id;
    }

    protected function createComponentAddRoomToConferenceForm(): AddRoomToConference\AddRoomToConferenceControl
    {
        $control = $this->addRoomToConferenceControlFactory->create();

        $conferenceId = (int) $this->getParameter('id');
        $control->setConferenceId($conferenceId);

        return $control;
    }
}