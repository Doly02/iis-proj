<?php

declare(strict_types=1);

namespace App\ReservationModule\Controls\DetailReservation;

use App\TicketModule\Model\TicketService;
use Nette\Application\UI\Control;
use Nette\Database\Table\ActiveRow;
use Nette\Security\User;
use App\ReservationModule\Model\ReservationService;

final class DetailReservationControl extends Control
{
    /** @var User */
    private $user;
    private ReservationService $reservationService;

    private TicketService $ticketService;

    private int $reservationId;

    public function __construct(User $user, ReservationService $reservationService, TicketService $ticketService)
    {
        $this->user = $user;
        $this->reservationService = $reservationService;
        $this->ticketService = $ticketService;
    }

    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/../../templates/ReservationDetail/detail.latte');
        $this->template->render();
    }

    public function setReservationId(int $id): void
    {
        $this->reservationId = $id;
    }
}
