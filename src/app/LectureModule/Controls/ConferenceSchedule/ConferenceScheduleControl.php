<?php

namespace App\LectureModule\Controls\ConferenceSchedule;

use App\ConferenceModule\Model\ConferenceService;
use Nette\Application\UI\Control;
use Nette\Security\User;

final class ConferenceScheduleControl extends Control
{
    private User $user;
    private int $conferenceId;
    private array $xItems = [];
    private array $yTimes = [];
    private array $yItems = [];
    private array $scheduleItems = [];
    private $conferenceService;

    public function __construct(
        User $user, int $conferenceId,
        array $xItems, array $yTimes, array $yItems, array $scheduleItems, ConferenceService $conferenceService)
    {
        $this->user = $user;
        $this->conferenceId = $conferenceId;
        $this->xItems = $xItems;
        $this->yTimes = $yTimes;
        $this->yItems = $yItems;
        $this->scheduleItems = $scheduleItems;
        $this->conferenceService = $conferenceService;
    }

    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/../../templates/ConferenceSchedule/scheduleControl.latte');

        $this->template->user = $this->user;
        $this->template->conferenceId = $this->conferenceId;
        $this->template->xItems = $this->xItems;
        $this->template->yTimes = $this->yTimes;
        $this->template->yItems = $this->yItems;
        $this->template->scheduleItems = $this->scheduleItems;

        $this->template->isCreator = false;
        $conference = $this->conferenceService->fetchById($this->conferenceId);
        if($this->user->getId())
            $this->template->isCreator = $conference->organiser_id == $this->user->getId();

        $this->template->render();
    }
}
