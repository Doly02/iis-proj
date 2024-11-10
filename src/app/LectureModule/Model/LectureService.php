<?php

namespace App\LectureModule\Model;

use App\CommonModule\Model\BaseService;
use DateTime;
use Nette\Database\Explorer;
use Nette\Database\Row;
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

    public function getPresentationByLectureId(int $lectureId): ?Row
    {
        // Dotaz na získanie jednej prezentácie s daným lecture_id
        $presentation = $this->database->query('
        SELECT * FROM presentations
        WHERE lecture_id = ?
        LIMIT 1
    ', $lectureId)->fetch();

        return $presentation ?: null;
    }


    public function getLecturerName(int $presentationId): ?string
    {
        $presentation = $this->database->table('presentations')->get($presentationId);

        if (!$presentation || !$presentation->lecturer_id) {
            return null;
        }

        $lecturer = $this->database->table('users')->get($presentation->lecturer_id);

        if (!$lecturer) {
            return null;
        }

        return $lecturer->name . ' ' . $lecturer->surname;
    }

    public function getLectureTimeMarkers(int $conferenceId): array
    {
        $lectures = $this->database->table('lectures')
            ->where('conference_has_rooms.conference_id', $conferenceId);

        $timeMarkers = [];

        foreach ($lectures as $lecture) {
            $startTime = (new \DateTime($lecture->start_time))->format('Y-m-d H:i');
            $endTime = (new \DateTime($lecture->end_time))->format('Y-m-d H:i');
            $timeMarkers[$startTime] = $startTime;
            $timeMarkers[$endTime] = $endTime;
        }

        asort($timeMarkers);

        return array_values($timeMarkers);
    }

    function calculateRowspan($start, $end, $times): int
    {
        $startIndex = array_search($start, $times);
        $endIndex = array_search($end, $times);

        if ($startIndex !== false && $endIndex !== false) {
            return $endIndex - $startIndex;
        }

        return 1;
    }


    public function getConferenceScheduleItems(int $conferenceId): array
    {
        $rooms = $this->getRooms($conferenceId);

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
            $presentation = $this->getPresentationByLectureId($lecture->id);

            $lectureRoomName = $rooms[$lectureRoomId] ?? 'Unknown Room';


            $timeMarkers = $this->getLectureTimeMarkers($conferenceId);

            $start = (new \DateTime($lecture->start_time))->format('Y-m-d H:i');
            $end = (new \DateTime($lecture->end_time))->format('Y-m-d H:i');

            $rowspan = $this->calculateRowspan($start, $end, $timeMarkers);

            $item = [
                'time' => $lectureStart->format('Y-m-d H:i'),
                'room' => $lectureRoomName,
                'start' => $lectureStart->format('H:i'),
                'end' => $lectureEnd->format('H:i'),
                'rowspan' => $rowspan,
                'name' => $presentation ? $presentation['name'] : "",
                'lecturer' => $presentation ? $this->getLecturerName($presentation->id) : "",
            ];

            $scheduleItems[] = $item;
        }

        return $scheduleItems;
    }




}