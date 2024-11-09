<?php

declare(strict_types=1);

namespace App\RoomModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use App\CommonModule\Presenters\SecurePresenter;
use App\ConferenceModule\Controls\ListConferenceCreator\ListConferenceCreatorControl;
use App\ConferenceModule\Controls\ListConferenceCreator\IListConferenceCreatorControlFactory;
use App\RoomModule\Controls\ListRoom\IListRoomControlFactory;
use App\RoomModule\Controls\ListRoom\ListRoomControl;

final class RoomListPresenter extends SecurePresenter
{
    private IListRoomControlFactory $_listRoomControlFactory;

    public function __construct(IListRoomControlFactory $listRoomControlFactory)
    {
        parent::__construct();
        $this->_listRoomControlFactory = $listRoomControlFactory;
    }

    protected function createComponentListRoom(): ListRoomControl
    {
        return $this->_listRoomControlFactory->create();
    }

    public function renderDefault(): void
    {
        $this->template->title = 'List of Rooms';
    }
}
