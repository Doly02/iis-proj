<?php

declare(strict_types=1);

namespace App\ConferenceModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use App\ConferenceModule\Controls\ListConferenceCreator\ListConferenceCreatorControl;
use App\ConferenceModule\Controls\ListConferenceCreator\IListConferenceCreatorControlFactory;

final class ConferenceListCreatorPresenter extends BasePresenter
{
    private IListConferenceCreatorControlFactory $listConferenceCreatorControlFactory;

    public function __construct(IListConferenceCreatorControlFactory $listConferenceCreatorControlFactory)
    {
        parent::__construct();
        $this->listConferenceCreatorControlFactory = $listConferenceCreatorControlFactory;
    }

    protected function createComponentListConferenceCreator(): ListConferenceCreatorControl
    {
        return $this->listConferenceCreatorControlFactory->create();
    }

    public function renderDefault(): void
    {
        $this->template->title = 'The Conferences That You Organize';
    }
}
