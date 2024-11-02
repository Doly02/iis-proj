<?php

declare(strict_types=1);

namespace App\ReservationModule\Presenters;

use App\CommonModule\Presenters\SecurePresenter;
use App\ReservationModule\Controls\ReserveRegistered\IReserveRegisteredControlFactory;
use App\ReservationModule\Controls\ReserveRegistered\ReserveRegisteredControl;
use App\ReservationModule\Model\ReservationService;
use Nette\Application\UI\Form;
use Tracy\Debugger;

final class ReserveRegisteredPresenter extends SecurePresenter
{
    private $_reservationService;
    private $_reserveRegisteredControlFactory;

    public function __construct(
        IReserveRegisteredControlFactory $reserveControlFactory,
        ReservationService $reservationService
    )
    {
        parent::__construct();
        Debugger::log("Reached construct", 'info');
        $this->_reserveRegisteredControlFactory = $reserveControlFactory;
        $this->_reservationService = $reservationService;
    }

    public function actionMakeReservation(int $id): void
    {
        Debugger::log("Reached actionMakeReservation with ID: $id", 'info');
        $this->checkPrivilege();
        /* Load Data And Prepare Before Render Of Template */
        // \Tracy\Debugger::log('Reached actionMakeReservation in presenter.');
        // \Tracy\Debugger::barDump($id, 'Conference ID');
        // Debugger::log('Reached actionMakeReservation with ID: ' . $id, 'info');

        $this->template->conferenceId = $id;
    }

    protected function createComponentReserveRegisteredForm(): ReserveRegisteredControl
    {
        Debugger::log("Reached create", 'info');
        $this->checkPrivilege();

        /* Creation Of Controller Instance */
        $control = $this->_reserveRegisteredControlFactory->create();

        /* Get ConferenceId From URL Parameter */
        $conferenceId = (int) $this->getParameter('id');
        $control->setConferenceId($conferenceId); // Set conferenceId

        return $control;    /* Return Whole Controller */
    }

    public function handleReservation(string $firstName, string $lastName, string $email, int $tickets, int $conferenceId, $costumerId): void
    {
        Debugger::log("Reached handle", 'info');
        $this->checkPrivilege();

        try {
            $this->reservationService->reserveTickets($firstName, $lastName, $email, $tickets, $conferenceId, $costumerId);
            $this->flashMessage('Reservation completed successfully!', 'success');
            $this->redirect('this'); // Redirect to Actual Page
        }
        catch (\Exception $e)
        {
            $this->flashMessage('Error: ' . $e->getMessage(), 'error');
        }
    }

    public function onSuccessReserveForm(Form $form, \stdClass $values) : void
    {
        $this->checkPrivilege();

        try {
            $this->reservationService->reserveTickets(
                $values->firstName,
                $values->lastName,
                $values->email,
                $values->tickets,
                (int) $values->conferenceId,
                (int) $values->costumerId
            );
            $this->presenter->flashMessage('Your tickets have been reserved successfully.', 'success');
            $this->presenter->redirect('this');
        }
        catch (\Exception $e)
        {
            \Tracy\Debugger::log($e, \Tracy\ILogger::EXCEPTION);
            $form->addError('An error occurred while reserving tickets1: ' . $e->getMessage());
        }
    }

    /* Render The Template */
    public function renderDefault(int $conferenceId): void
    {
        Debugger::log("Reached render", 'info');
        $this->checkPrivilege();
        $this->template->conferenceId = $conferenceId;
    }
}