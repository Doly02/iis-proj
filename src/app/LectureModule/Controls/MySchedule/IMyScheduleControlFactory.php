<?php

namespace App\LectureModule\Controls\MySchedule;

use Nette\Database\DateTime;
interface IMyScheduleControlFactory
{
    public function create(
        array $xItems,
        array $yItems,
        array $scheduleItems,
        int $week,
        string $year,
        DateTime $monday,
        DateTime $sunday): MyScheduleControl;
}