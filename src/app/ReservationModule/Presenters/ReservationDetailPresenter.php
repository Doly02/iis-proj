<?php

namespace App\ReservationModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use App\ConferenceModule\Model\ConferenceService;
use App\ReservationModule\Model\ReservationService;
use App\TicketModule\Model\TicketService;
use Nette\Security\User;

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

        // Load Reservation
        $reservation = $this->reservationService->fetchById($reservationId);

        // Check If Exists Reservation
        if (!$reservation) {
            $this->flashMessage('Reservation not found.', 'error');
            $this->redirect(':CommonModule:Home:default');
        }

        // Check If Reservation Belong To Logged-In User
        if ($reservation->customer_id != $this->user->getId()) {
            $this->flashMessage('You do not have permission to view this reservation.', 'error');
            $this->redirect(':CommonModule:Home:default');
        }

        $this->template->reservation = $reservation;
        $this->template->conference = $this->conferenceService->fetchById($reservation->conference_id);
        $this->template->reservationId = $reservationId;
        $this->template->tickets = $this->ticketService->getTicketsForPaidReservation($reservationId);
    }

}
