<?php

namespace App\LectureModule\Controls\LecturerSchedule;

interface ILecturerScheduleControlFactory
{
    public function create(
        array $xItems,
        array $yItems,
        array $scheduleItems): LecturerScheduleControl;
}