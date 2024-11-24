<?php

namespace App\ConferenceModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use App\ConferenceModule\Controls\EditConference;
use App\ConferenceModule\Model\ConferenceService;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Tracy\Debugger;

final class EditConferencePresenter extends BasePresenter
{
    private $editConferenceControlFactory;
    private ConferenceService $conferenceService;

    public function __construct(EditConference\IEditConferenceControlFactory $EditConferenceControlFactory, ConferenceService $conferenceService)
    {
        parent::__construct();
        $this->editConferenceControlFactory = $EditConferenceControlFactory;
        $this->conferenceService = $conferenceService;
    }

    protected function createComponentEditConferenceForm(): EditConference\EditConferenceControl
    {
        $control = $this->editConferenceControlFactory->create();

        $conferenceId = (int) $this->getParameter('id');
        $control->setConferenceId($conferenceId);

        return $control;
    }

    public function actionDelete(int $id): void
    {
        try {
            $this->conferenceService->deleteConferenceById($id);
            $this->flashMessage('Conference deleted successfully.', 'success');
        } catch (\Exception $e) {
            $this->flashMessage('Error deleting conference: ' . $e->getMessage(), 'error');
        }

        // Redirect to the creator list of conferences
        $this->redirect(':ConferenceModule:ConferenceListCreator:list');
    }

}