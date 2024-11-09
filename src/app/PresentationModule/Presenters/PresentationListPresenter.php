<?php

namespace App\PresentationModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use App\PresentationModule\Controls\ListPresentation\IListPresentationControlFactory;
use App\PresentationModule\Controls\ListPresentation\ListPresentationControl;
use Tracy\Debugger;

final class PresentationListPresenter extends BasePresenter
{
    private IListPresentationControlFactory $listPresentationControlFactory;
    private int $conferenceId;

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

    protected function createComponentPresentationGrid(): ListPresentationControl
    {
        $control = $this->listPresentationControlFactory->create();

        $control->setConferenceId($this->conferenceId);

        return $control;
    }
}
