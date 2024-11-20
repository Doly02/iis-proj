<?php

namespace App\LectureModule\Controls\PersonalSchedule;

interface IPersonalScheduleControlFactory
{
    public function create(
        array $xItems,
        array $yItems,
        array $scheduleItems): PersonalScheduleControl;
}