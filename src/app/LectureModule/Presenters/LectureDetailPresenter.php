<?php

namespace App\LectureModule\Presenters;

use App\CommonModule\Presenters\SecurePresenter;
use App\LectureModule\Model\LectureService;

final class LectureDetailPresenter extends SecurePresenter
{
    private $_lectureService;

    public function __construct(LectureService $lectureService)
    {
        parent::__construct();
        $this->_lectureService = $lectureService;
    }

    public function renderDefault(int $lectureId): void
    {
        $userId = $this->getUser()->getId();

        $conferenceId = $this->_lectureService->getConferenceByLectureId($lectureId);
        $conference = $this->_lectureService->getConferenceById($conferenceId);
        $organiserId = $conference->organiser_id;

        $lectureData = $this->_lectureService->getLectureDetail($lectureId);

        if (!$lectureData) {
            $this->flashMessage('Lecture not found.', 'error');
            $this->redirect('Homepage:default');
        }

        $this->template->lectureData = $lectureData;
        $this->template->userId = $userId;
        $this->template->organiserId = $organiserId;
    }

}