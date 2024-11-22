<?php

namespace App\ReservationModule\Controls\ReserveNonRegistered;

use App\ReservationModule\Model\ReservationService;
use App\UserModule\Model\UserService;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Utils\Validators;

final class ReserveNonRegisteredControl extends Control
{
    private $_reservationService;
    private $_conferenceId;
    private $_availableTickets;
    private $_ticketPrice;

    private UserService $_userService;
    public function __construct(
        ReservationService $reservationService,
        UserService $userService
    ) {
        $this->_reservationService = $reservationService;
        $this->_userService = $userService;
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
            }, 'You must reserve at least 1 ticket, the current number of available tickets is ' . $this->_availableTickets . ' tickets.')
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

        $form->addRadioList('paymentMethod', 'Payment Method', [
            'online' => 'Pay Online',
            'on_site' => 'Pay on Site',
        ])
            ->setDefaultValue('on_site')
            ->setRequired('Please select a payment method.')
            ->setHtmlAttribute('class', 'form-check');

        // Register Checkbox
        $registerCheckbox = $form->addCheckbox('register', 'Register me as a user')
            ->setHtmlAttribute('class', 'form-check-input')
            ->setHtmlId('register-checkbox');

        // Password Field
        $passwordField = $form->addPassword('password', 'Password')
            ->setHtmlAttribute('class', 'form-control password-fields')
            ->addConditionOn($registerCheckbox, [$registerCheckbox, 'isFilled'])
            ->setRequired('Please enter a password.');

        // Confirm Password Field
        $form->addPassword('passwordConfirm', 'Confirm Password')
            ->setHtmlAttribute('class', 'form-control password-fields')
            ->addConditionOn($registerCheckbox, [$registerCheckbox, 'isFilled'])
            ->setRequired('Please confirm your password.')
            ->addRule(function ($control) use ($form) {
                return $control->value === $form['password']->value;
            }, 'Passwords do not match.');


        /* Submission */
        $form->addSubmit('submit', 'Reserve Tickets')
            ->setHtmlAttribute('class', 'btn btn-primary');

        $form->onSuccess[] = [$this, 'onSuccessReserveForm'];
        return $form;
    }

    public function onSuccessReserveForm(Form $form, \stdClass $values): void
    {
        try {
            $isPaid = $values->paymentMethod === 'online' ? 1 : 0;
            $userId = null;

            if ($values->register) {
                $registrationData = [
                    'name' => $values->firstName,
                    'lastName' => $values->lastName,
                    'email' => $values->email,
                    'password' => $values->password,
                ];

                $userId = $this->_userService->registrateUserReturnId(
                    \Nette\Utils\ArrayHash::from($registrationData),
                    \App\UserModule\Enums\Role::USER
                );
            }

            $this->_reservationService->reserveTickets(
                $values->firstName,
                $values->lastName,
                $values->email,
                $values->tickets,
                $this->_conferenceId,
                $userId,
                $isPaid
            );

            $this->presenter->flashMessage(
                $isPaid
                    ? 'Your tickets have been reserved and paid online successfully.'
                    : 'Your tickets have been reserved. Please pay on site.',
                'success'
            );

            $this->presenter->redirect(':ConferenceModule:ConferenceList:list');
        }
        catch (\Nette\Application\AbortException $e)
        {
            $this->presenter->redirect(':ConferenceModule:ConferenceList:list');
            // Ignore This Type of Exception
        }
        catch (\Exception $e)
        {
            \Tracy\Debugger::log($e, \Tracy\ILogger::EXCEPTION);
            $form->addError('An error occurred while processing your request: ' . $e->getMessage());
        }
    }

    public function render(): void
    {
        $this->template->ticketPrice = $this->_ticketPrice;
        $this->template->setFile(__DIR__ . '/../../templates/ReserveNonRegistered/makeReservation.latte');
        $this->template->render();
    }
}