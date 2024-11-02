<?php

namespace App\ReservationModule\Controls\ReserveNonRegistered;

use App\ReservationModule\Model\ReservationService;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Utils\Validators;

final class ReserveNonRegisteredControl extends Control
{
    private $_reservationService;
    private $_conferenceId;
    private $_availableTickets;
    private $_ticketPrice;

    public function __construct(
        ReservationService $reservationService
    )
    {
        $this->_reservationService = $reservationService;
    }

    public function setConferenceId(int $conferenceId) : void
    {
        $this->_conferenceId = $conferenceId;
    }

    public function setAvailableTickets(int $availableTickets) : void
    {
        $this->_availableTickets = $availableTickets;
    }

    public function setTicketPrice(float $ticketPrice) : void
    {
        $this->_ticketPrice = $ticketPrice;
    }

    protected function createComponentReserveNonRegisteredForm(): \Nette\Application\UI\Form
    {
        $form = new Form();

        /* Fist Name */
        $form->addText('firstName', 'First Name')
            ->setRequired('Please enter your first name.')
            ->setHtmlAttribute('class', 'form-control');

        /* Last Name */
        $form->addText('lastName', 'Last Name')
            ->setRequired('Please enter your last name.')
            ->setHtmlAttribute('class', 'form-control');

        /* Email Address */
        $form->addText('email', 'Email')
            ->setRequired('Please enter your email.')
            ->addRule(function ($control) {
                return Validators::isEmail($control->value);
            }, 'Please enter a valid email address.')
            ->setHtmlAttribute('class', 'form-control');

        /* Number of Tickets */
        $form->addText('tickets', 'Tickets')
            ->setDefaultValue(1)
            ->setRequired('Please select the number of tickets.')
            ->addRule(function ($control) {
                $value = (int) $control->getValue();
                return Validators::isNumeric($control->getValue()) && $value >= 1 && $value <= $this->_availableTickets;
            }, 'You must reserve at least 1 ticket and no more than ' . $this->_availableTickets . ' tickets.')
            ->setHtmlAttribute('class', 'form-control')
            ->setHtmlAttribute('id', 'ticket-quantity');

        /* Hidden Button + */
        $form->addButton('addTicket', '+')
            ->setHtmlAttribute('class', 'btn btn-secondary')
            ->setHtmlAttribute('onclick', 'adjustTicketCount(1)');

        /* Hidden Button - */
        $form->addButton('removeTicket', '-')
            ->setHtmlAttribute('class', 'btn btn-secondary')
            ->setHtmlAttribute('onclick', 'adjustTicketCount(-1)');

        $form->addHidden('conferenceId', (string) $this->_conferenceId);

        /* Submission */
        $form->addSubmit('submit', 'Reserve Tickets')
            ->setHtmlAttribute('class', 'btn btn-primary');

        $form->onSuccess[] = [$this, 'onSuccessReserveForm'];
        return $form;
    }

    public function onSuccessReserveForm(Form $form, \stdClass $values): void
    {
        try {
            $this->_reservationService->reserveTickets(
                $values->firstName,
                $values->lastName,
                $values->email,
                $values->tickets,
                (int) $values->conferenceId,
                null
            );
            $this->presenter->flashMessage('Your tickets have been reserved successfully.', 'success');
            $this->presenter->redirect(':ConferenceModule:ConferenceList:list');
        }
        catch (\Nette\Application\AbortException $e)
        {
           /* This Exception Is Part Of Application Flow, So Do Not Bother */
           throw $e;
        }
        catch (\Exception $e)
        {
            \Tracy\Debugger::log($e, \Tracy\ILogger::EXCEPTION);
            $form->addError('An error occurred while reserving tickets2: ' . $e->getMessage());
        }
    }

    public function render(): void
    {
        $this->template->ticketPrice = $this->_ticketPrice;
        $this->template->setFile(__DIR__ . '/../../templates/ReserveNonRegistered/makeReservation.latte');
        $this->template->render();
    }
}