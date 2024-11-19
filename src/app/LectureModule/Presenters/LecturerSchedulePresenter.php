<?php

namespace App\LectureModule\Presenters;

use App\CommonModule\Presenters\SecurePresenter;
use App\LectureModule\Controls\LecturerSchedule\LecturerScheduleControl;
use App\LectureModule\Controls\LecturerSchedule\ILecturerScheduleControlFactory;
use App\LectureModule\Model\LectureService;
use Nette\Database\DateTime;
use Nette\Security\User;

class LecturerSchedulePresenter extends SecurePresenter
{
    private ILecturerScheduleControlFactory $lecturerScheduleControlFactory;
    private LectureService $lectureService;
    private User $user;

    public function __construct(
        ILecturerScheduleControlFactory $lecturerScheduleControlFactory,
        LectureService $lectureService,
        User $user
    ) {
        parent::__construct();
        $this->lecturerScheduleControlFactory = $lecturerScheduleControlFactory;
        $this->lectureService = $lectureService;
        $this->user = $user;
    }

    public function actionLecturerSchedule(): void
    {
        $userId = $this->user->getId();

        $xItems = $this->lectureService->getLectureDatesByLecturerId($userId);
        $yItems = $this->lectureService->getLectureTimeMarkersByLecturerId($userId);
        $scheduleItems = $this->lectureService->getLecturerScheduleItems($userId);

        $this->template->xItems = $xItems;
        $this->template->yItems = $yItems;
        $this->template->scheduleItems = $scheduleItems;
    }

    protected function createComponentLecturerSchedule(): LecturerScheduleControl
    {
        $xItems = $this->template->xItems;
        $yItems = $this->template->yItems;
        $scheduleItems = $this->template->scheduleItems;

        return $this->lecturerScheduleControlFactory->create(
            $xItems, $yItems, $scheduleItems
        );
    }
}



