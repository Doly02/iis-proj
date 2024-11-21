<?php

namespace App\PresentationModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use App\ConferenceModule\Model\ConferenceService;
use App\PresentationModule\Model\PresentationService;
use App\UserModule\Model\UserService;
use Nette\Security\User;
use Tracy\Debugger;

final class LecturerPresentationDetailPresenter extends BasePresenter
{
    private $PresentationService;
    private $user;
    private $conferenceService;
    private $userService;

    public function __construct(PresentationService $PresentationService, User $user, ConferenceService $conferenceService,
                                UserService $userService)
    {
        $this->PresentationService = $PresentationService;
        $this->user = $user;
        $this->conferenceService = $conferenceService;
        $this->userService = $userService;
    }

    public function renderDefault(int $id): void
    {
        $presentationId = $id;
        $presentation = $this->PresentationService->fetchById($presentationId);
        $lecturerId = $presentation->lecturer_id;

        $conference = $this->conferenceService->fetchById($presentation->conference_id);
        $lecturerInfo = $this->userService->getUserById($lecturerId);

        $this->template->lecturerInfo = $lecturerInfo;

        $this->template->conference = $conference;
        $this->template->conferenceId = $conference->id;
        $this->template->presentation = $presentation;
        $this->template->presentationId = $presentationId;
    }

}
