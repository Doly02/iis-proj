<?php

namespace App\LectureModule\Controls\ConferenceSchedule;

use Nette\Application\UI\Control;
use Nette\Security\User;

final class ConferenceScheduleControl extends Control
{
    private User $user;
    private int $conferenceId;
    private array $xItems = [];
    private array $xTimes = [];
    private array $yItems = [];
    private array $scheduleItems = [];

    public function __construct(
        User $user, int $conferenceId,
        array $xItems, array $xTimes, array $yItems, array $scheduleItems)
    {
        $this->user = $user;
        $this->conferenceId = $conferenceId;
        $this->xItems = $xItems;
        $this->xTimes = $xTimes;
        $this->yItems = $yItems;
        $this->scheduleItems = $scheduleItems;
    }

    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/../../templates/ConferenceSchedule/scheduleControl.latte');

        $this->template->user = $this->user;
        $this->template->conferenceId = $this->conferenceId;
        $this->template->xItems = $this->xItems;
        $this->template->xTimes = $this->xTimes;
        $this->template->yItems = $this->yItems;
        $this->template->scheduleItems = $this->scheduleItems;

        $this->template->render();
    }
}
