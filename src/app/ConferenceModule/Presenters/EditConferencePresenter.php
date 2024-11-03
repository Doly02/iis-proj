<?php

namespace App\ConferenceModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use App\ConferenceModule\Controls\EditConference;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Tracy\Debugger;

final class EditConferencePresenter extends BasePresenter
{
    private $editConferenceControlFactory;

    public function __construct(EditConference\IEditConferenceControlFactory $EditConferenceControlFactory)
    {
        parent::__construct();
        $this->editConferenceControlFactory = $EditConferenceControlFactory;
    }

    protected function createComponentEditConferenceForm(): EditConference\EditConferenceControl
    {
        $control = $this->editConferenceControlFactory->create();

        $conferenceId = (int) $this->getParameter('id');
        $control->setConferenceId($conferenceId);

        return $control;
    }

}