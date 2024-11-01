<?php

namespace App\ConferenceModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use App\ConferenceModule\Controls\AddConference;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;

final class ConferenceAddPresenter extends BasePresenter
{
    private $addConferenceControlFactory;

    public function __construct(AddConference\IAddConferenceControlFactory $addConferenceControlFactory)
    {
        parent::__construct();
        $this->addConferenceControlFactory = $addConferenceControlFactory;
    }

    protected function createComponentAddConferenceForm(): AddConference\AddConferenceControl
    {
        return $this->addConferenceControlFactory->create();
    }

    public function addConferenceFormSucceeded(Form $form, \stdClass $values): void
    {
        try
        {
            $this->flashMessage('Adding conference successful!');
            $this->redirect('Homepage:default');
        }
        catch (AuthenticationException $e)
        {
            $form->addError('An error occurred while adding your conference.');
        }
    }
}