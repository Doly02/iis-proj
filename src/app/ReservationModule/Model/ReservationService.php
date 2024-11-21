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
            ->sum('num_reserved_tickets') ?: 0;
    }

    public function getAvailableTickets(int $conferenceId): int
    {
        $conference = $this->getConferenceById($conferenceId);
        if (!$conference) {
            return 0;
        }

        $totalCapacity = (int) $conference->capacity;
        $reservedTickets = $this->getReservedTicketsCount($conferenceId);

        return max(0, $totalCapacity - $reservedTickets);
    }

    public function getUserReservations(int $userId): array
    {
        /* Load Reservation of Specific User */
        $reservations = $this->database->table('reservations')
            ->where('customer_id', $userId);

        $result = [];

        foreach ($reservations as $reservation) {
            /* Load Conferences Based on conference_id */
            $conference = $this->database->table('conferences')->get($reservation->conference_id);

            /* Open All Keys From Reservation And Add Conference Name */
            $result[] = array_merge($reservation->toArray(), [
                'conference_name' => $conference ? $conference->name : null,
            ]);
        }

        return $result;
    }

    public function reserveTickets(string $firstName, string $lastName, string $email, int $tickets, int $conferenceId, ?int $costumer_id, int $isPaid) : void
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
                'is_paid' => $isPaid,
                'conference_id' => $conferenceId,
                'num_reserved_tickets' => $tickets,
                'customer_id' => $costumer_id
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

    public function getReservationsByConferenceId(int $conferenceId): array
    {
        $sql = "
        SELECT reservations.*, conferences.name AS conference_name
        FROM reservations
        JOIN conferences ON reservations.conference_id = conferences.id
        WHERE reservations.conference_id = ?
    ";

        return $this->database->query($sql, $conferenceId)->fetchAll();
    }


    /**
     * Get User's Reserved Conferences
     *
     * @param int $userId
     * @return Selection
     */
    public function getUserReservedConferences(int $userId): Selection
    {
        return $this->database->table($this->getTableName())
            ->where('customer_id', $userId)
            ->select('conference_id');
    }

    public function deleteReservationsByUserId(int $userId): void
    {
        $this->database->table($this->getTableName())
            ->where('customer_id', $userId)
            ->delete();
    }

    public function approveReservation(int $reservationId): void
    {
        $this->database->table('reservations')
            ->where('id', $reservationId)
            ->update(['is_paid' => 1]);
    }

    /**
     * Joins conference name with reservations
     */
    public function getTableWithConferenceName(int $userId): array
    {
        // SQL query to fetch reservations with conference name, filtered by user_id
        $sql = "
            SELECT reservations.id AS reservation_id, reservations.*, conferences.name AS conference_name
            FROM reservations
            LEFT JOIN conferences ON reservations.conference_id = conferences.id
            WHERE reservations.customer_id = ?
        ";

        // Execute the query with the userId parameter
        return $this->database->query($sql, $userId)->fetchAll();
    }
    /*public function getTableWithConferenceName(): array
    {
        // Reference the view directly as if it were a table
        $sql = "
        SELECT reservations.id AS reservation_id, reservations.*, conferences.name AS conference_name
        FROM reservations
        LEFT JOIN conferences ON reservations.conference_id = conferences.id
        ";

        return $this->database->query($sql)->fetchAll();
    }*/
}
