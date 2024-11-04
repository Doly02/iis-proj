<?php

namespace App\ConferenceModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use App\ConferenceModule\Controls\DetailConference\DetailConferenceControl;
use App\ConferenceModule\Controls\DetailConference\IDetailConferenceControlFactory;
use App\ConferenceModule\Model\ConferenceService;
use Nette\Security\User;

final class ConferenceDetailPresenter extends BasePresenter
{
    private $conferenceService;
    private $user;

    public function __construct(ConferenceService $conferenceService, User $user)
    {
        $this->conferenceService = $conferenceService;
        $this->user = $user;
    }

    public function renderDefault(int $id): void
    {
        $conference = $this->conferenceService->getConferenceById($id);
        $userId = $this->user->getId();
        $organiserId = $conference->id;

        $start_time_formatted = date('d.m.Y H:i', strtotime($conference->start_time));
        $end_time_formatted = date('d.m.Y H:i', strtotime($conference->end_time));

        // Pass userId and organizerId to display buttons
        $this->template->userId = $userId;
        $this->template->organiserId = $organiserId;

        // Pass both the conference and formatted times to the template
        $this->template->conference = $conference;
        $this->template->start_time_formatted = $start_time_formatted;
        $this->template->end_time_formatted = $end_time_formatted;
    }

}
