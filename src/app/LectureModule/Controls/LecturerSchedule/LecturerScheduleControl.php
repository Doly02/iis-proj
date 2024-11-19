<?php

namespace App\LectureModule\Controls\LecturerSchedule;

use Nette\Application\UI\Control;
use Nette\Security\User;

final class LecturerScheduleControl extends Control
{
    private User $user;
    private array $xItems = [];
    private array $yItems = [];
    private array $scheduleItems = [];

    public function __construct(
        User $user,
        array $xItems, array $yItems, array $scheduleItems)
    {
        $this->user = $user;
        $this->xItems = $xItems;
        $this->yItems = $yItems;
        $this->scheduleItems = $scheduleItems;
    }

    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/../../templates/LecturerSchedule/lecturerScheduleControl.latte');

        $this->template->user = $this->user;
        $this->template->xItems = $this->xItems;
        $this->template->yItems = $this->yItems;
        $this->template->scheduleItems = $this->scheduleItems;

        $this->template->render();
    }
}