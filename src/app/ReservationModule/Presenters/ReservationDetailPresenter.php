<?php

namespace App\ReservationModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use App\ConferenceModule\Model\ConferenceService;
use App\ReservationModule\Model\ReservationService;
use App\TicketModule\Model\TicketService;
use Nette\Security\User;
use Tracy\Debugger;

final class ReservationDetailPresenter extends BasePresenter
{
    private $reservationService;
    private $user;
    private $ticketService;
    private $conferenceService;


    public function __construct(ReservationService $reservationService, User $user, TicketService $ticketService,
                                ConferenceService $conferenceService)
    {
        parent::__construct();
        $this->reservationService = $reservationService;
        $this->user = $user;
        $this->ticketService = $ticketService;
        $this->conferenceService = $conferenceService;
    }

    public function actionDefault(int $id): void
    {
        if (!$this->user->isLoggedIn()) {
            $this->redirect(':CommonModule:Login:default');
        }

        $reservationId = $id;
        $reservation = $this->reservationService->fetchById($reservationId);

        if (!$reservation) {
            $this->flashMessage('Reservation not found.', 'error');
            $this->redirect(':ConferenceModule:ConferenceList:list');
        }

        $conferenceId = $reservation->conference_id;
        bdump($conferenceId);
        $conference = $this->conferenceService->fetchById($conferenceId);

        // Check If Reservation Belong To Logged-In User
        if ($reservation->customer_id != $this->user->getId()) {
            $this->flashMessage('You do not have permission to view this reservation.', 'error');
            $this->redirect(':ConferenceModule:ConferenceList:list');
        }

        $this->template->reservation = $reservation;
        $this->template->conference = $conference;
        $this->template->conference = $this->conferenceService->fetchById($reservation->conference_id);
        $this->template->reservationId = $reservationId;
        $this->template->tickets = $this->ticketService->getTicketsForPaidReservation($reservationId);
    }

}
