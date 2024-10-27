<?php

namespace App\ConferenceModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use App\ConferenceModule\Model\ConferenceService;

final class ConferenceListPresenter extends BasePresenter
{

    private ConferenceService $conferenceService;

    public function __construct(ConferenceService $conferenceService)
    {
        parent::__construct();
        $this->conferenceService = $conferenceService;
    }

    public function renderList(): void
    {
        $this->template->conferences = $this->conferenceService->getConferenceTable();
    }
}