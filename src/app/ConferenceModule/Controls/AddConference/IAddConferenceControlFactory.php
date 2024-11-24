<?php

declare(strict_types=1);

namespace App\ConferenceModule\Controls\AddConference;

interface IAddConferenceControlFactory
{
    function create(): \App\ConferenceModule\Controls\AddConference\AddConferenceControl;
}
