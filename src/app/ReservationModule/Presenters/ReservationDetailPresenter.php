<?php

namespace App\ReservationModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use App\ReservationModule\Controls\DetailReservation\DetailReservationControl;
use App\ReservationModule\Controls\DetailReservation\IDetailReservationControlFactory;
use App\ReservationModule\Model\ReservationService;
use Nette\Security\User;

final class ReservationDetailPresenter extends BasePresenter
{
    private $reservationService;
    private $user;

    private $detailReservationControlFactory;

    public function __construct(ReservationService $reservationService, User $user, IDetailReservationControlFactory $detailReservationControlFactory)
    {
        parent::__construct();
        $this->reservationService = $reservationService;
        $this->user = $user;
        $this->detailReservationControlFactory = $detailReservationControlFactory;
    }

    protected function createComponentReservationDetail(): DetailReservationControl
    {
        $control = $this->detailReservationControlFactory->create();

        $reservationId = (int) $this->getParameter('id');
        $control->setReservationId($reservationId);

        return $control;
    }

    public function actionDefault(): void
    {
        if (!$this->user->isLoggedIn()) {
            $this->redirect(':CommonModule:Login:default');
        }
    }

}
