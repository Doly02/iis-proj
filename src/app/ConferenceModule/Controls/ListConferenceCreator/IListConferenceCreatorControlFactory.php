<?php

declare(strict_types=1);

namespace App\ConferenceModule\Controls\ListConferenceCreator;

interface IListConferenceCreatorControlFactory
{
    public function create(): ListConferenceCreatorControl;
}
