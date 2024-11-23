<?php

namespace App\ConferenceModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use App\CommonModule\Presenters\SecurePresenter;
use App\ConferenceModule\Controls\ListConference\IListConferenceControlFactory;
use App\ConferenceModule\Controls\ListConference\ListConferenceControl;
use App\ConferenceModule\Controls\ListConferenceAdmin\IListConferenceAdminControlFactory;
use App\ConferenceModule\Controls\ListConferenceAdmin\ListConferenceAdminControl;

final class ConferenceListAdminPresenter extends BasePresenter
{
    private IListConferenceAdminControlFactory $listConferenceAdminControlFactory;

    public function __construct(IListConferenceAdminControlFactory $listConferenceAdminControlFactory)
    {
        parent::__construct();
        $this->listConferenceAdminControlFactory = $listConferenceAdminControlFactory;
    }

    protected function createComponentConferenceAdminGrid(): ListConferenceAdminControl
    {
        return $this->listConferenceAdminControlFactory->create();
    }

    public function renderDefault(): void
    {
        $this->template->title = 'All conferences (admin)';
    }
}
