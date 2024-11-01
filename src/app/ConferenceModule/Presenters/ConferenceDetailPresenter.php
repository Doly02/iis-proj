<?php

namespace App\ConferenceModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use App\ConferenceModule\Controls\DetailConference\DetailConferenceControl;
use App\ConferenceModule\Controls\DetailConference\IDetailConferenceControlFactory;
use App\ConferenceModule\Model\ConferenceService;

final class ConferenceDetailPresenter extends BasePresenter
{
    private $conferenceService;

    public function __construct(ConferenceService $conferenceService)
    {
        $this->conferenceService = $conferenceService;
    }

    public function renderDefault(int $id): void
    {
        $conference = $this->conferenceService->getConferenceById($id);
        $this->template->conference = $conference;
    }
}
