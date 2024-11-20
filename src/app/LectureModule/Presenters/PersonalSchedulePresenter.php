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
    private int $conferenceId;

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

    public function actionPersonalSchedule($id): void
    {
        $this->conferenceId = $id;

        $conference = $this->lectureService->getConferenceById($this->conferenceId);

        if (!$conference) {
            $this->error('Conference not found');
        }

        $yItems = $this->lectureService->getLectureTimeMarkers($this->conferenceId);
        $xItems = $this->lectureService->getRoomNames($this->conferenceId);


        $yTimes = array_map(function($item) {
            return (new DateTime($item))->format('H:i');
        }, $yItems);

        $scheduleItems = $this->lectureService->getConferenceScheduleItems($this->conferenceId);

        $this->template->conferenceId = $id;
        $this->template->conference = $conference;
        $this->template->xItems = $xItems;
        $this->template->yTimes = $yTimes;
        $this->template->yItems = $yItems;
        $this->template->scheduleItems = $scheduleItems;
    }

    protected function createComponentPersonalSchedule(): PersonalScheduleControl
    {
        $conferenceId = $this->template->conferenceId;
        $xItems = $this->template->xItems;
        $yTimes = $this->template->yTimes;
        $yItems = $this->template->yItems;
        $scheduleItems = $this->template->scheduleItems;

        return $this->personalScheduleControlFactory->create(
            $conferenceId, $xItems, $yTimes, $yItems, $scheduleItems
        );
    }
}



