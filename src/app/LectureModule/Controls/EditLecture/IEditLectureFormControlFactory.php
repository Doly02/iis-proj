<?php

namespace App\LectureModule\Controls\EditLecture;

interface IEditLectureFormControlFactory
{
    public function create(int $lectureId): EditLectureFormControl;
}