<?php

namespace App\LectureModule\Model;

use App\CommonModule\Model\BaseService;
use DateTime;
use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;

final class LectureService extends BaseService
{
    public function __construct(Explorer $database)
    {
        parent::__construct($database);
    }

    public function getTableName(): string
    {
        return 'lectures';
    }

    public function getConferenceById(int $conferenceId): ?ActiveRow
    {
        return $this->database->table('conferences')
            ->get($conferenceId);
    }

    public function getConferenceRooms(int $conferenceId): array
    {
        return $this->database->table('rooms')
            ->where(':conference_has_rooms.conference_id', $conferenceId)
            ->fetchAll();
    }


    public function getConferenceAndRoomId(int $conferenceId, int $roomId): ?int
    {
        $row = $this->database->table('conference_has_rooms')
            ->where('conference_id', $conferenceId)
            ->where('room_id', $roomId)
            ->fetch();

        return $row ? $row->id : null;
    }

    public function getRoomBookingRange(int $conferenceId): ?array
    {
        $row = $this->getConferenceById($conferenceId);
        return $row ? ['start' => $row->start_time, 'end' => $row->end_time] : null;
    }


    public function addLecture(int $conferenceAndRoomId, DateTime $startTime, DateTime $endTime): void
    {
        $this->database->table('lectures')->insert([
            'id_conference_has_rooms' => $conferenceAndRoomId,
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);
    }

    public function isTimeSlotAvailable(int $conferenceAndRoomId, DateTime $startTime, DateTime $endTime): bool
    {
        $conflictingLecture = $this->database->table('lectures')
            ->where('id_conference_has_rooms', $conferenceAndRoomId)
            ->where('NOT (end_time <= ? OR start_time >= ?)', $startTime, $endTime)
            ->fetch();

        return $conflictingLecture === null;
    }

    public function getTimeSlots(int $conferenceId): array
    {
        $conference = $this->database->table('conferences')->get($conferenceId);

        if (!$conference) {
            throw new \Exception('Conference not found');
        }

        // Načítame dátum konferencie a nastavíme ho do startTime a endTime
        $conferenceDate = (new DateTime($conference->start_time))->format('Y-m-d');

        // Zaokrúhlenie startTime nadol na najbližšiu celú hodinu s dátumom konferencie
        $startTime = new DateTime($conference->start_time);
        $startTime->setTime((int)$startTime->format('H'), 0);

        // Zaokrúhlenie endTime nahor na najbližšiu celú hodinu s dátumom konferencie
        $endTime = new DateTime($conference->end_time);
        if ((int)$endTime->format('i') > 0) {
            $endTime->modify('+1 hour');
        }
        $endTime->setTime((int)$endTime->format('H'), 0);

        $timeSlots = [];

        while ($startTime <= $endTime) {
            // Nastavíme časový slot s kompletným dátumom a časom
            $timeSlots[] = $startTime->format('Y-m-d H:i');
            $startTime->add(new \DateInterval('PT1H')); // Pridá jednu hodinu
        }

        return $timeSlots;
    }


    public function getRoomNames(int $conferenceId): array
    {
        return $this->database->table('rooms')
            ->where(':conference_has_rooms.conference_id', $conferenceId)
            ->fetchPairs(null, 'name');
    }

    public function getRooms(int $conferenceId): array
    {
        $rooms = $this->database->table('rooms')
            ->where(':conference_has_rooms.conference_id', $conferenceId)
            ->fetchPairs('id', 'name');

        return $rooms;
    }


    public function getConferenceScheduleItems(int $conferenceId): array
    {
        $rooms = $this->getRooms($conferenceId); // Načítanie miestností pre konferenciu
        $timeSlots = $this->getTimeSlots($conferenceId); // Načítanie časových slotov pre konferenciu

        $lectures = $this->database->query('
            SELECT lectures.*, conference_has_rooms.room_id
            FROM lectures
            JOIN conference_has_rooms ON lectures.id_conference_has_rooms = conference_has_rooms.id
            WHERE conference_has_rooms.conference_id = ?
        ', $conferenceId)->fetchAll();

        $scheduleItems = [];

        foreach ($lectures as $lecture) {
            $lectureRoomId = $lecture->room_id;
            $lectureStart = new \DateTime($lecture->start_time);
            $lectureEnd = new \DateTime($lecture->end_time);

            $lectureRoomName = $rooms[$lectureRoomId] ?? 'Unknown Room';

            foreach ($timeSlots as $timeSlot) {
                $slotTime = new \DateTime($timeSlot);

                if ($slotTime >= $lectureStart && $slotTime < $lectureEnd) {
                    $scheduleItems[] = [
                        'time' => $timeSlot,
                        'room' => $lectureRoomName,
                    ];
                }
            }

        }

        return $scheduleItems;
    }



}