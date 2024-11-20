<?php

namespace App\LectureModule\Presenters;

use App\CommonModule\Presenters\SecurePresenter;
use App\LectureModule\Controls\PersonalSchedule\PersonalScheduleControl;
use App\LectureModule\Controls\PersonalSchedule\IPersonalScheduleControlFactory;
use App\LectureModule\Model\LectureService;
use Nette\Database\DateTime;
use Nette\Security\User;

class PersonalSchedulePresenter extends SecurePresenter
{
    private IPersonalScheduleControlFactory $personalScheduleControlFactory;
    private LectureService $lectureService;
    private User $user;

    public function __construct(
        IPersonalScheduleControlFactory $personalScheduleControlFactory,
        LectureService $lectureService,
        User $user
    ) {
        parent::__construct();
        $this->personalScheduleControlFactory = $personalScheduleControlFactory;
        $this->lectureService = $lectureService;
        $this->user = $user;
    }

    public function actionPersonalSchedule(): void
    {
        $userId = $this->user->getId();

        /*$xItems = $this->lectureService->getLectureDatesByPersonalId($userId);
        $yItems = $this->lectureService->getLectureTimeMarkersByPersonalId($userId);
        $scheduleItems = $this->lectureService->getPersonalScheduleItems($userId);

        $this->template->xItems = $xItems;
        $this->template->yItems = $yItems;
        $this->template->scheduleItems = $scheduleItems;*/
    }

    protected function createComponentPersonalSchedule(): PersonalScheduleControl
    {
        $xItems = $this->template->xItems;
        $yItems = $this->template->yItems;
        $scheduleItems = $this->template->scheduleItems;

        return $this->personalScheduleControlFactory->create(
            $xItems, $yItems, $scheduleItems
        );
    }
}



