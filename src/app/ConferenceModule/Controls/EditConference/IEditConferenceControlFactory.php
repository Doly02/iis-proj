<?php

declare(strict_types=1);

namespace App\ConferenceModule\Controls\EditConference;

interface IEditConferenceControlFactory
{
    function create(): \App\ConferenceModule\Controls\EditConference\EditConferenceControl;
}
