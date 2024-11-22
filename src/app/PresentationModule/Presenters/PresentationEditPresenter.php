<?php

namespace App\PresentationModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use App\PresentationModule\Controls\EditPresentation;
use App\PresentationModule\Model\PresentationService;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Tracy\Debugger;

final class PresentationEditPresenter extends BasePresenter
{
    private PresentationService $PresentationService;

    public function __construct(PresentationService $PresentationService)
    {
        parent::__construct();
        $this->PresentationService = $PresentationService;
    }

    public function actionDeny(int $id, int $conferenceId): void
    {
        try {
            $this->PresentationService->denyPresentationById($id);
            $this->flashMessage('Presentation denied successfully.', 'success');
        } catch (\Exception $e) {
            $this->flashMessage('Error denying Presentation: ' . $e->getMessage(), 'error');
        }

        // Redirect to the list of Presentations
        $this->redirect(':PresentationModule:PresentationList:organizerList');
    }

    public function actionApprove(int $id, int $conferenceId): void
    {
        try {
            $this->PresentationService->approvePresentationById($id);
            $this->flashMessage('Presentation approved successfully.', 'success');
        } catch (\Exception $e) {
            $this->flashMessage('Error approving Presentation: ' . $e->getMessage(), 'error');
        }

        // Redirect to the list of Presentations
        $this->redirect(':PresentationModule:PresentationList:organizerList');
    }

    public function actionDelete(int $id): void
    {
        try {
            $this->PresentationService->deletePresentation($id);
            $this->flashMessage('Presentation deleted successfully.', 'success');
        } catch (\Exception $e) {
            $this->flashMessage('Error deleting Presentation: ' . $e->getMessage(), 'error');
        }

        // Redirect to the list of Presentations
        $this->redirect(':PresentationModule:LecturerPresentationList:list');
    }


}