<?php

namespace App\ReservationModule\Model;

use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;
use Nette\Utils\ArrayHash;
use App\CommonModule\Model\BaseService;
use Nette\Security\SimpleIdentity;
use App\TicketModule\Model\TicketService;
use Exception;
use Tracy\Debugger;

final class ReservationService extends BaseService
{
    public function getTableName(): string
    {
        return 'reservations';
    }

    public function getConferenceById(int $conferenceId): ?ActiveRow
    {
        return $this->database->table('conferences')
            ->get($conferenceId);
    }

    public function getReservedTicketsCount(int $conferenceId): int
    {
        return $this->database->table('reservations')
            ->where('conference_id', $conferenceId)
            ->sum('tickets') ?: 0;
    }

    public function reserveTickets(string $firstName, string $lastName, string $email, int $tickets, int $conferenceId) : void
    {
        $this->database->beginTransaction();
        $errorMessage = 'Reservation could not be completed.';

        try {
            Debugger::log('Attempting reservation for: ' . $email);

            /* Load of Ticket Price From Table Tickets Based on conference_id */
            $ticket = $this->database->table('tickets')
                ->where('conference_id', $conferenceId)
                ->fetch();

            if (!$ticket)
            {
                throw new Exception('Ticket price not found for the selected conference.');
            }

            /* Calculate Price For Tickets */
            $pricePerTicket = (float) $ticket->price;
            $totalPrice = $pricePerTicket * $tickets;

            /* Insert Record To The Reservation Table */
            $reservation = $this->getTable()->insert([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'created_date' => new \DateTime(),  // Actual Date
                'created_time' => new \DateTime(),  // Actual Time
                'price_to_pay' => $totalPrice,
                'conference_id' => $conferenceId,
                'num_reserved_tickets' => $tickets,
                'customer_id' => null               // Costumer is Not In The System
            ]);

            if (!$reservation instanceof ActiveRow) {
                $this->database->rollBack();
                throw new Exception("Failed to create reservation.");
            }

            /* Get Available Tickets Using TicketService */
            $ticketService = new TicketService($this->database);
            $availableTickets = $ticketService->getAvailableTickets($conferenceId, $tickets);

            if (count($availableTickets) < $tickets) {
                $this->database->rollBack();
                throw new Exception("Not enough available tickets for the selected conference.");
            }

            /* Assign Available Tickets to Reservation */
            $ticketService->assignTicketsToReservation($availableTickets, $reservation->id);

            $this->database->commit();
        }
        catch (\Nette\Database\UniqueConstraintViolationException $e)
        {
            $this->database->rollBack();
            throw new Exception('A reservation with this email already exists: ' . $e->getMessage());
        }
        catch (\Exception $e)
        {
            $this->database->rollBack();
            Debugger::log('Error: ' . $e->getMessage(), 'error');
            throw new Exception($errorMessage);
        }
    }

}
