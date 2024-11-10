<?php

namespace App\LectureModule\Controls\ConferenceSchedule;

interface IConferenceScheduleControlFactory
{
    public function create(
        int $conferenceId,
        array $xItems,
        array $xTimes,
        array $yItems,
        array $scheduleItems): ConferenceScheduleControl;
}


