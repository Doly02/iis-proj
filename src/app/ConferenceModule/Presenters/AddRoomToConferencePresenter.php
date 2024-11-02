<?php

namespace App\ConferenceModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use App\ConferenceModule\Controls\AddRoomToConference;;

final class AddRoomToConferencePresenter extends BasePresenter
{
    private $addRoomToConferenceControlFactory;

    public function __construct(AddRoomToConference\IAddRoomToConferenceControlFactory $addRoomToConferenceControlFactory)
    {
        parent::__construct();
        $this->addRoomToConferenceControlFactory = $addRoomToConferenceControlFactory;
    }
}