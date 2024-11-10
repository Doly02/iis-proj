<?php

namespace App\LectureModule\Controls\AddNewLecture;

use App\LectureModule\Model\LectureService;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Security\User;
use DateTime;

final class NewLectureFormControl extends Control
{
    private User $user;
    private int $conferenceId;
    private LectureService $lectureService;

    public function __construct(
        User $user, LectureService $lectureService, int $conferenceId
    ) {
        $this->user = $user;
        $this->lectureService = $lectureService;
        $this->conferenceId = $conferenceId;
    }

    protected function createComponentNewLectureForm(): Form
    {
        $form = new Form;

        $rooms = $this->lectureService->getConferenceRooms($this->conferenceId);
        $roomOptions = [];
        foreach ($rooms as $room) {
            $roomOptions[$room->id] = $room->name;
        }

        $form->addSelect('room_id', 'Room', $roomOptions)
            ->setPrompt('Select room')
            ->setRequired('Select room');

        $conferenceRange = $this->lectureService->getRoomBookingRange($this->conferenceId);

        if ($conferenceRange) {
            $form->addText('start_time', 'Start of the lecture')
                ->setHtmlType('datetime-local')
                ->setRequired('Select start time')
                ->setHtmlAttribute('min', $conferenceRange['start']->format('Y-m-d\TH:i'))
                ->setHtmlAttribute('max', $conferenceRange['end']->format('Y-m-d\TH:i'));

            $durationOptions = [
                '15' => '15 min',
                '30' => '30 min',
                '45' => '45 min',
                '60' => '1 h',
                '90' => '1,5 h',
                '120' => '2 h',
                '150' => '2,5 h',
                '180' => '3 h',
                '210' => '3,5 h',
                '240' => '4 h',
                '270' => '4,5 h',
                '300' => '5 h',
                '330' => '5,5 h',
                '360' => '6 h',
                '390' => '6,5 h',
                '420' => '7 h',
                '450' => '7,5 h',
                '480' => '8 h',
            ];

            $form->addSelect('duration', 'Duration', $durationOptions)
                ->setPrompt('Select duration')
                ->setRequired('Select duration.');
        }

        $form->addSubmit('send', 'Add new lecture');

        $form->onSuccess[] = [$this, 'addLectureFormSucceeded'];
        return $form;
    }


    public function addLectureFormSucceeded(Form $form, \stdClass $values): void
    {
        $startTime = new DateTime($values->start_time);
        $durationMinutes = (int) $values->duration;

        $endTime = clone $startTime;
        $endTime->modify("+{$durationMinutes} minutes");

        $conferenceRange = $this->lectureService->getRoomBookingRange($this->conferenceId);

        if ($endTime > $conferenceRange['end']) {
            $form->addError('The lecture exceeds the time scope of the conference.');
            return;
        }

        $conferenceAndRoomId = $this->lectureService->getConferenceAndRoomId($this->conferenceId, $values->room_id);
        if ($conferenceAndRoomId === null) {
            $form->addError('Failed to match lecture to the conference.');
            return;
        }

        if (!$this->lectureService->isTimeSlotAvailable($conferenceAndRoomId, $startTime, $endTime)) {
            $form->addError('The selected time slot is already occupied by another lecture.');
            return;
        }

        $this->lectureService->addLecture($conferenceAndRoomId, $startTime, $endTime);

        $this->presenter->flashMessage('The lecture has been successfully added.', 'success');
        $this->presenter->redirect('this');
    }


    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/../../templates/AddNewLecture/default.latte');
        $this->template->render();
    }
}
