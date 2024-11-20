<?php

namespace App\LectureModule\Controls\PersonalSchedule;

use App\LectureModule\Model\LectureService;

interface IPersonalScheduleControlFactory
{
    public function create(
        int $conferenceId,
        array $xItems,
        array $yTimes,
        array $yItems,
        array $scheduleItems,
        LectureService $lectureService): PersonalScheduleControl;
}