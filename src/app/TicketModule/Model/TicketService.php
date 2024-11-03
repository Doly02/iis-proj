<?php

namespace App\TicketModule\Model;

use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;
use Nette\Utils\ArrayHash;
use App\CommonModule\Model\BaseService;
use Nette\Security\SimpleIdentity;
use Exception;
use Tracy\Debugger;
use Nette\Utils\Random;

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

    public function generateUniqueTicketCode(int $length = 10): string
    {
        do {
            // Generate a random alphanumeric code
            $code = Random::generate($length, 'A-Z0-9');

            // Check if the code already exists in the tickets table
            $exists = $this->database->table('tickets')
                    ->where('code', $code)
                    ->count('*') > 0;

        } while ($exists); // Repeat until a unique code is found

        return $code;
    }

    public function generateTickets(int $conferenceId, int $limit, int $price): void
    {
        for($i = 0; $i < $limit; $i++) {
            $uniqueCode = $this->generateUniqueTicketCode();
            $this->getTable()->insert([
                'conference_id' => $conferenceId,
                'reservation_id' => null,
                'code' => $uniqueCode,
                'price' => $price,
            ]);
        }
        // TODO unique code
    }
}