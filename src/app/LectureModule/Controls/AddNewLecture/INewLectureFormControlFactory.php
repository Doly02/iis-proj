<?php

namespace App\LectureModule\Controls\AddNewLecture;

interface INewLectureFormControlFactory
{
    public function create(int $conferenceId): NewLectureFormControl;
}
