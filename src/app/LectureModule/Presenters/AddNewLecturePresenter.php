<?php

namespace App\LectureModule\Presenters;

use App\CommonModule\Presenters\SecurePresenter;
use App\LectureModule\Controls\AddNewLecture\NewLectureFormControl;
use App\LectureModule\Controls\AddNewLecture\INewLectureFormControlFactory;

class AddNewLecturePresenter extends SecurePresenter
{
    private INewLectureFormControlFactory $newLectureFormControlFactory;

    public function __construct(INewLectureFormControlFactory $newLectureFormControlFactory)
    {
        parent::__construct();
        $this->newLectureFormControlFactory = $newLectureFormControlFactory;
    }

    public function actionDefault(int $conferenceId): void
    {
        $this->template->conferenceId = $conferenceId;
    }

    protected function createComponentNewLectureForm(): NewLectureFormControl
    {
        $conferenceId = $this->getParameter('conferenceId');
        return $this->newLectureFormControlFactory->create($conferenceId);
    }
}
