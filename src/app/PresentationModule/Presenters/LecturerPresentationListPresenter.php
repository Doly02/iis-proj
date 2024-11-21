<?php

namespace App\PresentationModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use App\PresentationModule\Controls\LecturerListPresentation\ILecturerListPresentationControlFactory;
use App\PresentationModule\Controls\LecturerListPresentation\LecturerListPresentationControl;
use Tracy\Debugger;

final class LecturerPresentationListPresenter extends BasePresenter
{
    private ILecturerListPresentationControlFactory $lecturerListPresentationControlFactory;

    public function __construct(ILecturerListPresentationControlFactory $lecturerListPresentationControlFactory)
    {
        parent::__construct();
        $this->lecturerListPresentationControlFactory = $lecturerListPresentationControlFactory;
    }

    public function actionList(): void
    {
        if (!$this->user->isLoggedIn()) {
            $this->redirect(':CommonModule:Login:default');
        }
    }


    protected function createComponentLecturerPresentationGrid(): LecturerListPresentationControl
    {
        return $this->lecturerListPresentationControlFactory->create();
    }
}
