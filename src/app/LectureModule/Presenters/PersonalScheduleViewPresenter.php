<?php

namespace App\LectureModule\Presenters;

use App\CommonModule\Presenters\SecurePresenter;
use App\LectureModule\Controls\PersonalScheduleView\PersonalScheduleViewControl;
use App\LectureModule\Controls\PersonalScheduleView\IPersonalScheduleViewControlFactory;
use App\LectureModule\Model\LectureService;
use Nette\Database\DateTime;
use Nette\Security\User;

class PersonalScheduleViewPresenter extends SecurePresenter
{
    private IPersonalScheduleViewControlFactory $personalScheduleViewControlFactory;
    private LectureService $lectureService;
    private User $user;
    private int $conferenceId;

    public function __construct(
        IPersonalScheduleViewControlFactory $personalScheduleViewControlFactory,
        LectureService $lectureService,
        User $user
    ) {
        parent::__construct();
        $this->personalScheduleViewControlFactory = $personalScheduleViewControlFactory;
        $this->lectureService = $lectureService;
        $this->user = $user;
    }

    public function actionPersonalScheduleView($id): void
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

    protected function createComponentPersonalScheduleView(): PersonalScheduleViewControl
    {
        $conferenceId = $this->template->conferenceId;
        $xItems = $this->template->xItems;
        $yTimes = $this->template->yTimes;
        $yItems = $this->template->yItems;
        $scheduleItems = $this->template->scheduleItems;

        return $this->personalScheduleViewControlFactory->create(
            $conferenceId, $xItems, $yTimes, $yItems, $scheduleItems, $this->lectureService
        );
    }
}



