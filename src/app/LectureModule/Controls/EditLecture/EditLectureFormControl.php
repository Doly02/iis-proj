<?php

namespace App\LectureModule\Controls\EditLecture;

use App\LectureModule\Model\LectureService;
use App\PresentationModule\Model\PresentationService;
use DateTime;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Security\User;

final class EditLectureFormControl extends Control
{
    private User $user;
    private int $lectureId;
    private LectureService $lectureService;
    private PresentationService $presentationService;

    public function __construct(
        User $user, int $lectureId, LectureService $lectureService, PresentationService $presentationService
    )
    {
        $this->user = $user;
        $this->lectureId = $lectureId;
        $this->lectureService = $lectureService;
        $this->presentationService = $presentationService;
    }

    protected function createComponentEditLectureForm(): Form
    {
        $form = new Form;

        $conferenceId = $this->lectureService->getConferenceByLectureId($this->lectureId);
        $lecture = $this->lectureService->getLectureById($this->lectureId);

        $rooms = $this->lectureService->getConferenceRooms($conferenceId);
        $roomOptions = [];
        foreach ($rooms as $room) {
            $roomOptions[$room->id] = $room->name;
        }

        $presentations = $this->presentationService->getConferenceApprovedPresentations($conferenceId);
        $presentationOptions = [];
        foreach ($presentations as $presentation) {
            $presentationOptions[$presentation->id] = $presentation->name;
        }

        $conferenceRange = $this->lectureService->getRoomBookingRange($conferenceId);

        $form->addSelect('room_id', 'Room', $roomOptions)
            ->setPrompt('Select room')
            ->setRequired('Select room')
            ->setDefaultValue($lecture->ref('conference_has_rooms')->room_id);

        if ($conferenceRange) {
            $form->addText('start_time', 'Start of the lecture')
                ->setHtmlType('datetime-local')
                ->setRequired('Select start time')
                ->setHtmlAttribute('min', $conferenceRange['start']->format('Y-m-d\TH:i'))
                ->setHtmlAttribute('max', $conferenceRange['end']->format('Y-m-d\TH:i'))
                ->setDefaultValue((new DateTime($lecture->start_time))->format('Y-m-d\TH:i'));

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

            $duration = (new DateTime($lecture->end_time))->getTimestamp() - (new DateTime($lecture->start_time))->getTimestamp();
            $durationInMinutes = $duration / 60;

            $form->addSelect('duration', 'Duration', $durationOptions)
                ->setPrompt('Select duration')
                ->setRequired('Select duration.')
                ->setDefaultValue((string)$durationInMinutes);

            $presentation = $this->lectureService->getPresentationByLectureId($this->lectureId);
            $defaultPresentationId = $presentation ? $presentation->id : null;

            $presentations = $this->lectureService->getConferenceApprovedPresentations($conferenceId);
            $presentationOptions = [];
            foreach ($presentations as $presentationItem) {
                $presentationOptions[$presentationItem->id] = $presentationItem->name;
            }

            $form->addSelect('presentation_id', 'Presentation', $presentationOptions)
                ->setPrompt('Select presentation')
                ->setDefaultValue($defaultPresentationId);
        }

        $form->addSubmit('send', 'Save changes');
        $form->onSuccess[] = [$this, 'editLectureFormSucceeded'];
        return $form;
    }

    public function editLectureFormSucceeded(Form $form, \stdClass $values): void
    {
        $startTime = new DateTime($values->start_time);
        $durationMinutes = (int)$values->duration;

        $endTime = clone $startTime;
        $endTime->modify("+{$durationMinutes} minutes");

        $conferenceId = $this->lectureService->getConferenceByLectureId($this->lectureId);
        $conferenceRange = $this->lectureService->getRoomBookingRange($conferenceId);

        if ($endTime > $conferenceRange['end']) {
            $form->addError('The lecture exceeds the time scope of the conference.');
            return;
        }

        $conferenceAndRoomId = $this->lectureService->getConferenceAndRoomId($conferenceId, $values->room_id);
        if ($conferenceAndRoomId === null) {
            $form->addError('Failed to match lecture to the conference.');
            return;
        }

        if (!$this->lectureService->isTimeSlotAvailableForEdit($conferenceAndRoomId, $startTime, $endTime, $this->lectureId)) {
            $form->addError('The selected time slot is already occupied by another lecture.');
            return;
        }

        $presentationId = $values->presentation_id ?? null;

        $this->lectureService->updateLecture($this->lectureId, $conferenceAndRoomId, $startTime, $endTime, $presentationId);
        if ($presentationId) {
            $this->presentationService->updateLectureId($this->lectureId, $presentationId);
        } else {
            $this->presentationService->clearLectureId($this->lectureId); // Pridaj metódu na odpojenie prezentácie
        }

        $this->presenter->flashMessage('The lecture has been successfully updated.', 'success');
        $this->presenter->redirect(':LectureModule:ConferenceSchedule:schedule', ['id' => $conferenceId]);
    }

    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/../../templates/EditLecture/default.latte');
        $this->template->render();
    }


}