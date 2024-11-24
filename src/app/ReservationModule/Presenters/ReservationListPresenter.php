<?php

namespace App\ReservationModule\Presenters;

use App\CommonModule\Presenters\SecurePresenter;
use App\ReservationModule\Controls\ListReservation\IListReservationControlFactory;
use App\ReservationModule\Controls\ListReservation\ListReservationControl;

final class ReservationListPresenter extends SecurePresenter
{
    private IListReservationControlFactory $listReservationControlFactory;

    public function __construct(IListReservationControlFactory $listReservationControlFactory)
    {
        parent::__construct();
        $this->listReservationControlFactory = $listReservationControlFactory;
    }

    protected function createComponentReservationGrid(): ListReservationControl
    {
        // $this->checkPrivilege();

        return $this->listReservationControlFactory->create();
    }
}
