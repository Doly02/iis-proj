<?php

namespace App\LectureModule\Presenters;

use App\CommonModule\Presenters\SecurePresenter;
use App\LectureModule\Controls\LecturerSchedule\LecturerScheduleControl;
use App\LectureModule\Controls\LecturerSchedule\ILecturerScheduleControlFactory;
use App\LectureModule\Model\LectureService;
use Nette\Database\DateTime;
use Nette\Security\User;
use function Symfony\Component\String\s;

class LecturerSchedulePresenter extends SecurePresenter
{
    private ILecturerScheduleControlFactory $lecturerScheduleControlFactory;
    private LectureService $lectureService;
    private User $user;

    public function __construct(
        ILecturerScheduleControlFactory $lecturerScheduleControlFactory,
        LectureService $lectureService,
        User $user
    ) {
        parent::__construct();
        $this->lecturerScheduleControlFactory = $lecturerScheduleControlFactory;
        $this->lectureService = $lectureService;
        $this->user = $user;
    }

    public function actionLecturerSchedule(): void
    {
        $userId = $this->user->getId();

        $xItems = $this->lectureService->getLectureDatesByLecturerId($userId);
        $yItems = $this->lectureService->getLectureTimeMarkersByLecturerId($userId);
        $scheduleItems = $this->lectureService->getLecturerScheduleItems($userId);

        $today = new DateTime();

        $week = isset($_GET['week']) ? (int)$_GET['week'] : (int)$today->format('W');
        $year = isset($_GET['year']) ? (int)$_GET['year'] : (int)$today->format('o');

        $monday = (new DateTime())->setISODate($year, $week, 1)->setTime(0, 0, 0);
        $sunday = (clone $monday)->modify('+6 days')->setTime(23, 59, 59);

        $currentWeekYear = (int)$monday->format('o');
        if ($currentWeekYear !== $year) {
            $year = $currentWeekYear;
        }

        $xItems = array_filter($xItems, function ($xItem) use ($monday, $sunday) {
            $date = DateTime::createFromFormat('d.m.Y', $xItem);
            return $date && $date >= $monday && $date <= $sunday;
        });

        $scheduleItems = array_filter($scheduleItems, function ($scheduleItem) use ($monday, $sunday) {
            $date = DateTime::createFromFormat('d.m.Y', $scheduleItem['date']);
            return $date && $date >= $monday && $date <= $sunday;
        });

        $this->template->xItems = $xItems;
        $this->template->yItems = $yItems;
        $this->template->scheduleItems = $scheduleItems;
        $this->template->week = $week;
        $this->template->year = $year;
        $this->template->monday = $monday;
        $this->template->sunday = $sunday;
    }

    protected function createComponentLecturerSchedule(): LecturerScheduleControl
    {
        $xItems = $this->template->xItems;
        $yItems = $this->template->yItems;
        $scheduleItems = $this->template->scheduleItems;
        $week = $this->template->week;
        $year = $this->template->year;
        $monday = $this->template->monday;
        $sunday = $this->template->sunday;


        return $this->lecturerScheduleControlFactory->create(
            $xItems, $yItems, $scheduleItems, $week, $year, $monday, $sunday
        );
    }
}



