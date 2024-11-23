<?php

namespace App\ConferenceModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use App\ConferenceModule\Model\ConferenceService;
use Nette\Security\User;
use Tracy\Debugger;

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
        $organiserId = $conference->organiser_id;

        $start_time_formatted = date('d.m.Y H:i', strtotime($conference->start_time));
        $end_time_formatted = date('d.m.Y H:i', strtotime($conference->end_time));

        // Pass userId and organizerId to display buttons
        $this->template->userId = $userId;
        $this->template->organiserId = $organiserId;
        \Tracy\Debugger::barDump($this->template->organiserId, 'organiserId');
        \Tracy\Debugger::barDump($this->template->userId, 'userId');

        // Pass both the conference and formatted times to the template
        $this->template->conference = $conference;
        $this->template->start_time_formatted = $start_time_formatted;
        $this->template->end_time_formatted = $end_time_formatted;

        // Room names
        $this->template->roomNames = $this->conferenceService->getRoomNamesByConferenceId($id);

        // Is customer
        $this->template->isCustomer = false;
        if($this->user->getId())
            $this->template->isCustomer = $this->conferenceService->hasUserReservedConference($id, $this->user->getId());

        $occupiedCapacity = $this->conferenceService->getOccupiedCapacity()[$conference->id] ?? 0;
        $this->template->occupiedCapacity = $occupiedCapacity;
    }
}
