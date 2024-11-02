<?php

declare(strict_types=1);

namespace App\ReservationModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use App\ReservationModule\Controls\ReserveNonRegistered\IReserveNonRegisteredControlFactory;
use App\ReservationModule\Controls\ReserveNonRegistered\ReserveNonRegisteredControl;
use App\ReservationModule\Model\ReservationService;
use Nette\Application\UI\Form;
use Tracy\Debugger;

final class ReserveNonRegisteredPresenter extends BasePresenter
{
    private $_reservationService;
    private $_reserveNonRegisteredControlFactory;

    private $_ticketPrice;

    private $_availableTickets;

    public function __construct(
        IReserveNonRegisteredControlFactory $reserveControlFactory,
        ReservationService $reservationService
    )
    {
        parent::__construct();
        $this->_reserveNonRegisteredControlFactory = $reserveControlFactory;
        $this->_reservationService = $reservationService;
        $this->_ticketPrice = 0;
        $this->_availableTickets = 0;
    }

    public function actionMakeReservation(int $id): void
    {
        /* Load Data And Prepare Before Render Of Template */
        \Tracy\Debugger::log('Reached actionMakeReservation in presenter.');
        \Tracy\Debugger::barDump($id, 'Conference ID');
        Debugger::log('Reached actionMakeReservation with ID: ' . $id, 'info');

        $conference = $this->_reservationService->getConferenceById($id);
        if ($conference)
        {
            $this->_availableTickets = $this->_reservationService->getAvailableTickets($id);
            if ($this->_availableTickets === 0) {
                $this->flashMessage('No tickets are available for this conference.', 'error');
                $this->redirect(':ConferenceModule:ConferenceList:list');
            }
            $this->_ticketPrice = $conference->price;
        }
        else
        {
            $this->_ticketPrice = 0;
        }
        $this->template->conferenceId = $id;
        $this->template->ticketPrice = $this->_ticketPrice;
    }

    protected function createComponentReserveNonRegisteredForm(): ReserveNonRegisteredControl
    {
        /* Creation Of Controller Instance */
        $control = $this->_reserveNonRegisteredControlFactory->create();

        /* Get ConferenceId From URL Parameter */
        $conferenceId = (int) $this->getParameter('id');
        $control->setConferenceId($conferenceId); // Set conferenceId
        $control->setTicketPrice($this->_ticketPrice);
        $control->setAvailableTickets($this->_availableTickets);

        return $control;    /* Return Whole Controller */
    }

    public function handleReservation(string $firstName, string $lastName, string $email, int $tickets, int $conferenceId, int $costumerId): void
    {
        \Tracy\Debugger::log('handleReservation called');
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

    public function onSuccessReserveForm(Form $form, \stdClass $values): void
    {
        try {
            $this->reservationService->reserveTickets(
                $values->firstName,
                $values->lastName,
                $values->email,
                $values->tickets,
                (int) $values->conferenceId,
                null
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
        $this->template->conferenceId = $conferenceId;
    }
}