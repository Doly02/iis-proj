<?php

namespace App\LectureModule\Controls\LecturerSchedule;

use Nette\Database\DateTime;

interface ILecturerScheduleControlFactory
{
    public function create(
        array $xItems,
        array $yItems,
        array $scheduleItems,
        int $week,
        string $year,
        DateTime $monday,
        DateTime $sunday): LecturerScheduleControl;
}