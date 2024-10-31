<?php

declare(strict_types=1);

namespace App\ConferenceModule\Controls\ListConference;

interface IListConferenceControlFactory
{
    function create(): ListConferenceControl;
}