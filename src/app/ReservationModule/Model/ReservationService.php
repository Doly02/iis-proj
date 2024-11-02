<?php

namespace App\ReservationModule\Model;

use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;
use Nette\Utils\ArrayHash;
use App\CommonModule\Model\BaseService;
use Nette\Security\SimpleIdentity;
use Exception;
use Tracy\Debugger;

final class ReservationService extends BaseService
{
    public function getTableName(): string
    {
        return 'reservations';
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
                'conference_id' => $conferenceId,   // TODO: How To Get ConferenceId?
                'customer_id' => null               // Costumer is Not In The System
            ]);

            if (!$reservation instanceof ActiveRow)
            {
                $this->database->rollBack();
                throw new Exception("Heyy");
            }


            /* Connection Of Reservation With Items In Table Tickets Based On Relationship With reservation_id */
            for ($i = 0; $i < $tickets; $i++)
            {
                $this->database->table('tickets')->insert([
                    'conference_id' => $conferenceId,
                    'reservation_id' => $reservation->id
                ]);
            }

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
