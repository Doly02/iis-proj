<?php

namespace App\LectureModule\Controls\PersonalSchedule;

use Nette\Application\UI\Control;
use Nette\Security\User;

final class PersonalScheduleControl extends Control
{
    private User $user;
    private int $conferenceId;
    private array $xItems = [];
    private array $yTimes = [];
    private array $yItems = [];
    private array $scheduleItems = [];

    public function __construct(
        User $user, int $conferenceId,
        array $xItems, array $yTimes, array $yItems, array $scheduleItems)
    {
        $this->user = $user;
        $this->conferenceId = $conferenceId;
        $this->xItems = $xItems;
        $this->yTimes = $yTimes;
        $this->yItems = $yItems;
        $this->scheduleItems = $scheduleItems;
    }

    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/../../templates/PersonalSchedule/personalScheduleControl.latte');

        $this->template->user = $this->user;
        $this->template->conferenceId = $this->conferenceId;
        $this->template->xItems = $this->xItems;
        $this->template->yTimes = $this->yTimes;
        $this->template->yItems = $this->yItems;
        $this->template->scheduleItems = $this->scheduleItems;

        $this->template->render();
    }
}