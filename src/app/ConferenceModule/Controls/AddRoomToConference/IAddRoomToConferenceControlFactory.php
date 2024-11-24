<?php

declare(strict_types=1);

namespace App\ConferenceModule\Controls\AddRoomToConference;

interface IAddRoomToConferenceControlFactory
{
    function create(): \App\ConferenceModule\Controls\AddRoomToConference\AddRoomToConferenceControl;
}
