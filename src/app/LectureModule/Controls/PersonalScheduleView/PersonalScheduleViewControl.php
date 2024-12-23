<?php

namespace App\LectureModule\Controls\PersonalScheduleView;

use App\LectureModule\Model\LectureService;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Security\User;

final class PersonalScheduleViewControl extends Control
{
    private User $user;
    private int $conferenceId;
    private array $xItems = [];
    private array $yTimes = [];
    private array $yItems = [];
    private array $scheduleItems = [];
    private LectureService $lectureService;

    public function __construct(
        User $user, int $conferenceId,
        array $xItems, array $yTimes, array $yItems, array $scheduleItems, LectureService $lectureService)
    {
        $this->user = $user;
        $this->conferenceId = $conferenceId;
        $this->xItems = $xItems;
        $this->yTimes = $yTimes;
        $this->yItems = $yItems;
        $this->scheduleItems = $scheduleItems;
        $this->lectureService = $lectureService;
    }

    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/../../templates/PersonalScheduleView/personalScheduleViewControl.latte');

        $this->template->user = $this->user;
        $this->template->conferenceId = $this->conferenceId;
        $this->template->xItems = $this->xItems;
        $this->template->yTimes = $this->yTimes;
        $this->template->yItems = $this->yItems;
        $this->template->scheduleItems = $this->scheduleItems;

        $this->template->render();
    }

}