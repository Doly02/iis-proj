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

        if (!$userId) {
            $this->flashMessage('You need to be logged in to view this page.', 'error');
            $this->redirect(':UserModule:Auth:signIn');
        }

        $lectureData = $this->_lectureService->getLectureDetail($lectureId);

        if (!$lectureData) {
            $this->flashMessage('Lecture not found.', 'error');
            $this->redirect('Homepage:default');
        }

        $this->template->lectureData = $lectureData;
    }

}