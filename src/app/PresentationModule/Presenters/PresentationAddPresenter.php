<?php

namespace App\PresentationModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use App\PresentationModule\Model\PresentationService;
use Nette\Application\UI\Form;
use Nette\Application\AbortException;
use Nette\Security\User;
use Tracy\Debugger;

final class PresentationAddPresenter extends BasePresenter
{

    private PresentationService $presentationService;
    private User $user;
    private int $conferenceId;

    public function __construct(PresentationService $presentationService, User $user)
    {
        parent::__construct();
        $this->presentationService = $presentationService;
        $this->user = $user;
    }

    public function actionAdd(int $id): void
    {
        if (!$this->user->isLoggedIn()) {
            $this->redirect(':CommonModule:Login:default');
        }
        $this->conferenceId = $id;
        Debugger::barDump($this->conferenceId);
    }

    protected function createComponentAddPresentationForm(): Form
    {
        $form = new Form;

        $form->addText('name', 'Name:')
            ->setRequired('Enter the presentation name.')
            ->addRule($form::MaxLength, 'Name can be at most 30 characters long.', 30);

        $form->addTextArea('description', 'Description:');

        $form->addTextArea('attachment', 'Attachment:');

        $form->addSubmit('send', 'Add');

        $form->onSuccess[] = [$this, 'addPresentationFormSucceeded'];
        return $form;
    }

    public function addPresentationFormSucceeded(Form $form, \stdClass $values): void
    {
        $userId = $this->user->getId();
        try {
            // Insert the room data into the database
            $this->presentationService->addPresentation([
                'name' => $values->name,
                'description' => $values->description,
                'attachment' => $values->attachment,
                'conference_id' => $this->conferenceId,
                'lecturer_id' => $userId,
                'state' => 'waiting'
            ]);

            $this->flashMessage('Presentation added successfully.', 'success');

            $this->redirect(destination: ':ConferenceModule:ConferenceList:list');


        } catch (AbortException $e) {
            // Allow AbortException silently (no action needed)
        } catch (\Exception $e) {
            $form->addError('An error occurred while requesting presentation: ' . $e->getMessage());
        }
        $this->redirect(':ConferenceModule:ConferenceList:list');
    }
}