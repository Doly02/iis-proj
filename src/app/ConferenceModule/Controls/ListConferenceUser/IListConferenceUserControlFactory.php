<?php

declare(strict_types=1);

namespace App\ConferenceModule\Controls\ListConferenceUser;

interface IListConferenceUserControlFactory
{
    public function create(): ListConferenceUserControl;
}
