<?php

namespace App\LectureModule\Controls\PersonalSchedule;

use App\LectureModule\Model\LectureService;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Security\User;

final class PersonalScheduleControl extends Control
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
        $this->template->setFile(__DIR__ . '/../../templates/PersonalSchedule/personalScheduleControl.latte');

        $this->template->user = $this->user;
        $this->template->conferenceId = $this->conferenceId;
        $this->template->xItems = $this->xItems;
        $this->template->yTimes = $this->yTimes;
        $this->template->yItems = $this->yItems;
        $this->template->scheduleItems = $this->scheduleItems;

        $this->template->render();
    }

    protected function createComponentScheduleForm(): Form
    {
        $form = new Form;

        foreach ($this->scheduleItems as $item) {
            $form->addCheckbox("lecture_{$item['id']}", "select");
        }

        $form->addSubmit('submit', 'Submit');

        $form->onSuccess[] = [$this, 'processScheduleForm'];

        return $form;
    }

    public function processScheduleForm(Form $form, \stdClass $values): void
    {
        $userId = $this->user->getId();
        // all
        $allLectures = array_column($this->scheduleItems, 'id');
        // selected
        $selectedLectures = [];
        foreach ($values as $key => $value) {
            if ($value) {
                $lectureId = str_replace('lecture_', '', $key);
                $selectedLectures[] = $lectureId;
            }
        }

        bdump($selectedLectures, 'Selected Lectures');
        bdump($allLectures, 'All Lectures');
        // updating selection
        $this->lectureService->saveSelectedLectures($userId, $selectedLectures, $allLectures);
        $this->presenter->redirect(':LectureModule:PersonalScheduleView:personalScheduleView', ['id' => $this->conferenceId]);
    }
}