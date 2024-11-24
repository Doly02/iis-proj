<?php

namespace App\ConferenceModule\Controls\ListConference;

interface IListConferenceControlFactory
{
    function create(): ListConferenceControl;
}