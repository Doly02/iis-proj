<?php

namespace App\LectureModule\Controls\PersonalSchedule;

interface IPersonalScheduleControlFactory
{
    public function create(
        int $conferenceId,
        array $xItems,
        array $yTimes,
        array $yItems,
        array $scheduleItems): PersonalScheduleControl;
}