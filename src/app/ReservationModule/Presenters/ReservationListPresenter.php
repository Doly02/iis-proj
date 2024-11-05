<?php

namespace App\ReservationModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use App\ReservationModule\Controls\ListReservation\IListReservationControlFactory;
use App\ReservationModule\Controls\ListReservation\ListReservationControl;

final class ReservationListPresenter extends BasePresenter
{
    private IListReservationControlFactory $listReservationControlFactory;

    public function __construct(IListReservationControlFactory $listReservationControlFactory)
    {
        parent::__construct();
        $this->listReservationControlFactory = $listReservationControlFactory;
    }

    protected function createComponentReservationGrid(): ListReservationControl
    {
        return $this->listReservationControlFactory->create();
    }
}
