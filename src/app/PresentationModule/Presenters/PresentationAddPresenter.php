<?php
namespace App\PresentationModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use App\PresentationModule\Model\PresentationService;
use Nette\Application\UI\Form;
use Nette\Application\AbortException;
use Nette\Database\Table\ActiveRow;
use Nette\Security\User;
use Tracy\Debugger;

final class PresentationAddPresenter extends BasePresenter
{
    private PresentationService $presentationService;
    private User $user;
    private int $conferenceId;
    private ?ActiveRow $presentation = null;

    public function __construct(PresentationService $presentationService, User $user)
    {
        parent::__construct();
        $this->presentationService = $presentationService;
        $this->user = $user;
    }

    public function actionAdd(int $id, ?int $presentationId = null): void
    {
        if (!$this->user->isLoggedIn()) {
            $this->redirect(':CommonModule:Login:default');
        }

        $this->conferenceId = $id;
        Debugger::barDump($this->conferenceId);

        if ($presentationId) {
            $this->presentation = $this->presentationService->fetchById($presentationId);
            if (!$this->presentation) {
                $this->flashMessage('Presentation not found.', 'error');
                $this->redirect(':ConferenceModule:ConferenceList:list');
            }
        }
    }

    public function actionEdit(int $id, ?int $presentationId = null): void
    {
        if (!$this->user->isLoggedIn()) {
            $this->redirect(':CommonModule:Login:default');
        }

        $this->conferenceId = $id;
        Debugger::barDump($this->conferenceId);

        if ($presentationId) {
            $this->presentation = $this->presentationService->fetchById($presentationId);
            if (!$this->presentation) {
                $this->flashMessage('Presentation not found.', 'error');
                $this->redirect(':ConferenceModule:ConferenceList:list');
            }
        }
    }

    protected function createComponentAddPresentationForm(): Form
    {
        $form = new Form;

        $form->addText('name', 'Name:')
            ->setRequired('Enter the presentation name.')
            ->addRule($form::MaxLength, 'Name can be at most 100 characters long.', 100);

        $form->addTextArea('description', 'Description:');
        $form->addTextArea('attachment', 'Attachment:');
        if ($this->presentation)
            $form->addSubmit('send', 'Edit');
        else
            $form->addSubmit('send', 'Add');

        if ($this->presentation) {
            $form->setDefaults([
                'name' => $this->presentation->name,
                'description' => $this->presentation->description,
                'attachment' => $this->presentation->attachment,
            ]);
        }

        $form->onSuccess[] = [$this, 'addPresentationFormSucceeded'];
        return $form;
    }

    public function addPresentationFormSucceeded(Form $form, \stdClass $values): void
    {
        $userId = $this->user->getId();
        try {
            if ($this->presentation) {
                $this->presentationService->updatePresentation($this->presentation->id, [
                    'name' => $values->name,
                    'description' => $values->description,
                    'attachment' => $values->attachment,
                    'conference_id' => $this->conferenceId,
                    'lecturer_id' => $userId,
                ]);
                $this->flashMessage('Presentation updated successfully.', 'success');
            } else {
                $this->presentationService->addPresentation([
                    'name' => $values->name,
                    'description' => $values->description,
                    'attachment' => $values->attachment,
                    'conference_id' => $this->conferenceId,
                    'lecturer_id' => $userId,
                    'state' => 'waiting',
                ]);
                $this->flashMessage('Presentation added successfully.', 'success');
            }
        } catch (AbortException $e) {
            // Allow AbortException silently (no action needed)
        } catch (\Exception $e) {
            $form->addError('An error occurred while processing the presentation: ' . $e->getMessage());
        }
        $this->redirect(':PresentationModule:LecturerPresentationList:list');
    }
}
