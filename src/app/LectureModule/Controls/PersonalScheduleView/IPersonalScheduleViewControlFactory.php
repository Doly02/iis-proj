<?php

namespace App\LectureModule\Controls\PersonalScheduleView;

use App\LectureModule\Model\LectureService;

interface IPersonalScheduleViewControlFactory
{
    public function create(
        int $conferenceId,
        array $xItems,
        array $yTimes,
        array $yItems,
        array $scheduleItems,
        LectureService $lectureService): PersonalScheduleViewControl;
}