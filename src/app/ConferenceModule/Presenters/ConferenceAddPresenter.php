<?php

namespace App\ConferenceModule\Presenters;

use App\CommonModule\Presenters\SecurePresenter;
use App\ConferenceModule\Controls\AddConference;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;

final class ConferenceAddPresenter extends SecurePresenter
{
    private $addConferenceControlFactory;

    public function __construct(AddConference\IAddConferenceControlFactory $addConferenceControlFactory)
    {
        parent::__construct();
        $this->addConferenceControlFactory = $addConferenceControlFactory;
    }

    public function startup(): void
    {
        parent::startup();
        if (!$this->getUser()->isLoggedIn())
        {
            $this->flashMessage('You Have To Log In For Access This Page.', 'warning');
            $this->redirect(':UserModule:Authentication:signIn');
        }
    }
    protected function createComponentAddConferenceForm(): AddConference\AddConferenceControl
    {
        $this->checkPrivilege();
        return $this->addConferenceControlFactory->create();
    }

    public function addConferenceFormSucceeded(Form $form, \stdClass $values): void
    {
        $this->checkPrivilege();

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