<?php

namespace App\ConferenceModule\Controls\ListConferenceAdmin;

interface IListConferenceAdminControlFactory
{
    function create(): ListConferenceAdminControl;
}