<?php

namespace App\ReservationModule\Controls\ReserveRegistered;

use App\ReservationModule\Model\ReservationService;
use App\UserModule\Model\UserService;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Security\User;
use Nette\Utils\Validators;

final class ReserveRegisteredControl extends Control
{
    private $_reservationService;
    private $_conferenceId;
    private $_userService;
    private $_user;
    private $_availableTickets;
    private $_ticketPrice;
    public function __construct(
        ReservationService $reservationService,
        UserService $userService,
        User $user
    )
    {
        $this->_reservationService = $reservationService;
        $this->_userService = $userService;
        $this->_user = $user;
    }

    public function setConferenceId(int $conferenceId): void
    {
        \Tracy\Debugger::log("Conference ID set to: $conferenceId", \Tracy\ILogger::INFO);
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

    protected function createComponentReserveRegisteredForm(): Form
    {
        $form = new Form();

        /* Number of Tickets */
        $form->addText('tickets', 'Tickets')
            ->setDefaultValue(1)
            ->setRequired('Please select the number of tickets.')
            ->addRule(function ($control) {
                $value = (int) $control->getValue();
                return Validators::isNumeric($control->getValue()) && $value >= 1 && $value <= $this->_availableTickets;
            }, 'You must reserve at least 1 ticket, the current number of available tickets is ' . $this->_availableTickets . ' tickets.')
            ->setHtmlAttribute('class', 'form-control')
            ->setHtmlAttribute('id', 'ticket-quantity');

        /* Button to Increase Ticket Quantity */
        $form->addButton('addTicket', '+')
            ->setHtmlAttribute('class', 'btn btn-secondary')
            ->setHtmlAttribute('onclick', 'adjustTicketCount(1)');

        /* Button to Decrease Ticket Quantity */
        $form->addButton('removeTicket', '-')
            ->setHtmlAttribute('class', 'btn btn-secondary')
            ->setHtmlAttribute('onclick', 'adjustTicketCount(-1)');

        $form->addRadioList('paymentMethod', 'Payment Method', [
            'online' => 'Pay Online',
            'on_site' => 'Pay on Site',
        ])
            ->setDefaultValue('on_site')
            ->setRequired('Please select a payment method.')
            ->setHtmlAttribute('class', 'form-check');

        $form->addSubmit('submit', 'Reserve')
            ->setHtmlAttribute('class', 'btn btn-primary');

        $form->onSuccess[] = [$this, 'onSuccessReserveForm'];
        return $form;
    }

    public function onSuccessReserveForm(Form $form, \stdClass $values): void
    {
        try
        {
            $isPaid = $values->paymentMethod === 'online' ? 1 : 0;

            $userId = $this->_user->getId();
            $user = $this->_userService->getUserById($userId);
            if (!$user) {
                throw new \Exception('User not found.');
            }

            $firstName = $user->name;
            $lastName = $user->surname;
            $email = $user->email;

            $this->_reservationService->reserveTickets(
                $firstName,
                $lastName,
                $email,
                (int) $values->tickets,
                (int) $this->_conferenceId,
                $userId,
                $isPaid
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
        $this->template->setFile(__DIR__ . '/../../templates/ReserveRegistered/makeReservation.latte');
        $this->template->render();
    }
}