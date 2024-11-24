<?php

namespace App\ConferenceModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use App\ConferenceModule\Controls\ListConference\IListConferenceControlFactory;
use App\ConferenceModule\Controls\ListConference\ListConferenceControl;

final class ConferenceListPresenter extends BasePresenter
{
    private IListConferenceControlFactory $listConferenceControlFactory;

    public function __construct(IListConferenceControlFactory $listConferenceControlFactory)
    {
        parent::__construct();
        $this->listConferenceControlFactory = $listConferenceControlFactory;
    }

    protected function createComponentConferenceGrid(): ListConferenceControl
    {
        return $this->listConferenceControlFactory->create();
    }
}
