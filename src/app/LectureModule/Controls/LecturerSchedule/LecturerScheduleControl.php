<?php

namespace App\LectureModule\Controls\LecturerSchedule;

use Nette\Application\UI\Control;
use Nette\Database\DateTime;
use Nette\Security\User;

final class LecturerScheduleControl extends Control
{
    private User $user;
    private array $xItems = [];
    private array $yItems = [];
    private array $scheduleItems = [];
    private int $week;
    private string $year;
    private DateTime $monday;
    private DateTime $sunday;


    public function __construct(
        User $user,
        array $xItems, array $yItems, array $scheduleItems,
        int $week, string $year, DateTime $monday, DateTime $sunday)
    {
        $this->user = $user;
        $this->xItems = $xItems;
        $this->yItems = $yItems;
        $this->scheduleItems = $scheduleItems;
        $this->week = $week;
        $this->year = $year;
        $this->monday = $monday;
        $this->sunday = $sunday;

    }

    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/../../templates/LecturerSchedule/lecturerScheduleControl.latte');

        $this->template->user = $this->user;
        $this->template->xItems = $this->xItems;
        $this->template->yItems = $this->yItems;
        $this->template->scheduleItems = $this->scheduleItems;
        $this->template->week = $this->week;
        $this->template->year = $this->year;
        $this->template->monday = $this->monday;
        $this->template->sunday = $this->sunday;

        $this->template->render();
    }
}