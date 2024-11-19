<?php

namespace App\LectureModule\Presenters;

use App\CommonModule\Presenters\SecurePresenter;
use App\LectureModule\Controls\EditLecture\EditLectureFormControl;
use App\LectureModule\Controls\EditLecture\IEditLectureFormControlFactory;
use App\LectureModule\Model\LectureService;

class EditLecturePresenter extends SecurePresenter
{
    private IEditLectureFormControlFactory $editLectureFormControlFactory;
    private lectureService $lectureService;

    public function __construct(
        IEditLectureFormControlFactory $editLectureFormControlFactory,
        lectureService $lectureService)
    {
        parent::__construct();
        $this->editLectureFormControlFactory = $editLectureFormControlFactory;
        $this->lectureService = $lectureService;
    }

    public function actionDefault(int $lectureId): void
    {
        $this->template->lectureId = $lectureId;
    }

    public function actionDelete(int $lectureid): void
    {
        $conferenceId = $this->lectureService->getConferenceByLectureId($lectureid);

        try {
            $this->lectureService->deleteLectureById($lectureid);
            $this->flashMessage('Conference deleted successfully.', 'success');
        } catch (\Exception $e) {
            $this->flashMessage('Error deleting lecture: ' . $e->getMessage(), 'error');
        }

        // Redirect to the conference schedule
        $this->redirect(':LectureModule:ConferenceSchedule:schedule', ['id' => $conferenceId]);
    }

    protected function createComponentEditLectureForm(): EditLectureFormControl
    {
        $lectureId = $this->getParameter('lectureId');
        return $this->editLectureFormControlFactory->create($lectureId);
    }
}

