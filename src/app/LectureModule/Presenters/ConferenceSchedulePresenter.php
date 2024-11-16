<?php

namespace App\LectureModule\Presenters;

use App\CommonModule\Presenters\SecurePresenter;
use App\LectureModule\Controls\ConferenceSchedule\ConferenceScheduleControl;
use App\LectureModule\Controls\ConferenceSchedule\IConferenceScheduleControlFactory;
use App\LectureModule\Model\LectureService;
use Nette\Database\DateTime;

class ConferenceSchedulePresenter extends SecurePresenter
{
    private IConferenceScheduleControlFactory $conferenceScheduleControlFactory;
    private LectureService $lectureService;
    private int $conferenceId;

    public function __construct(
        IConferenceScheduleControlFactory $conferenceScheduleControlFactory,
        LectureService $lectureService
    ) {
        parent::__construct();
        $this->conferenceScheduleControlFactory = $conferenceScheduleControlFactory;
        $this->lectureService = $lectureService;
    }

    public function actionSchedule(int $id): void
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

    public function actionPersonalSchedule(int $id): void
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

    protected function createComponentSchedule(): ConferenceScheduleControl
    {
        $conferenceId = $this->template->conferenceId;
        $xItems = $this->template->xItems;
        $yTimes = $this->template->yTimes;
        $yItems = $this->template->yItems;
        $scheduleItems = $this->template->scheduleItems;

        return $this->conferenceScheduleControlFactory->create(
            $conferenceId, $xItems, $yTimes, $yItems, $scheduleItems
        );
    }
}


