<?php

namespace App\TicketModule\Model;

use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;
use Nette\Utils\ArrayHash;
use App\CommonModule\Model\BaseService;
use Nette\Security\SimpleIdentity;
use Exception;
use Tracy\Debugger;

final class TicketService extends BaseService
{
    public function getTableName(): string
    {
        return 'tickets';
    }

    /**
     * Get Available Tickets For a Specific Conference.
     *
     * @param int $conferenceId
     * @param int $limit
     * @return array
     */
    public function getAvailableTickets(int $conferenceId, int $limit): array
    {
        /* Fetch Available Tickets (those without a reservation_id) */
        $tickets = $this->database->table($this->getTableName())
            ->where('conference_id', $conferenceId)
            ->where('reservation_id IS NULL')
            ->limit($limit)
            ->fetchAll();

        return $tickets;
    }

    /**
     * Assign Tickets To a Reservation.
     *
     * @param array $tickets
     * @param int $reservationId
     * @return void
     * @throws Exception If a Ticket Can't Be Updated
     */
    public function assignTicketsToReservation(array $tickets, int $reservationId): void
    {
        foreach ($tickets as $ticket) {
            if (!$ticket->update(['reservation_id' => $reservationId])) {
                throw new Exception("Failed to update ticket with ID: {$ticket->id}");
            }
        }
    }
}