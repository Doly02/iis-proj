<?php

namespace App\LectureModule\Presenters;

use App\CommonModule\Presenters\SecurePresenter;
use App\LectureModule\Controls\EditLecture\EditLectureFormControl;
use App\LectureModule\Controls\EditLecture\IEditLectureFormControlFactory;

class EditLecturePresenter extends SecurePresenter
{
    private IEditLectureFormControlFactory $editLectureFormControlFactory;

    public function __construct(IEditLectureFormControlFactory $editLectureFormControlFactory)
    {
        parent::__construct();
        $this->editLectureFormControlFactory = $editLectureFormControlFactory;
    }

    public function actionDefault(int $lectureId): void
    {
        $this->template->lectureId = $lectureId;
    }

    protected function createComponentEditLectureForm(): EditLectureFormControl
    {
        $lectureId = $this->getParameter('lectureId');
        return $this->editLectureFormControlFactory->create($lectureId);
    }
}

