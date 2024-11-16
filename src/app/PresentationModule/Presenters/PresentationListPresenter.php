<?php

namespace App\PresentationModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use App\PresentationModule\Controls\ListPresentation\IListPresentationControlFactory;
use App\PresentationModule\Controls\ListPresentation\ListPresentationControl;
use Tracy\Debugger;

final class PresentationListPresenter extends BasePresenter
{
    private IListPresentationControlFactory $listPresentationControlFactory;
    private ?int $conferenceId = null;

    public function __construct(IListPresentationControlFactory $listPresentationControlFactory)
    {
        parent::__construct();
        $this->listPresentationControlFactory = $listPresentationControlFactory;
    }

    public function actionList(int $id): void
    {
        if (!$this->user->isLoggedIn()) {
            $this->redirect(':CommonModule:Login:default');
        }
        $this->conferenceId = $id;
        Debugger::barDump($this->conferenceId);
    }

    public function actionOrganizerList(): void
    {
        if (!$this->user->isLoggedIn()) {
            $this->redirect(':CommonModule:Login:default');
        }
    }

    protected function createComponentPresentationGrid(): ListPresentationControl
    {
        $control = $this->listPresentationControlFactory->create();
        if($this->conferenceId)
            $control->setConferenceId($this->conferenceId);

        return $control;
    }
}
