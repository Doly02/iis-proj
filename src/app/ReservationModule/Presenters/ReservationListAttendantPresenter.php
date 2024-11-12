<?php

namespace App\ReservationModule\Presenters;

use App\CommonModule\Presenters\SecurePresenter;
use App\ReservationModule\Controls\ListReservationAttendant\IListReservationAttendantControlFactory;
use App\ReservationModule\Controls\ListReservationAttendant\ListReservationAttendantControl;
use App\ReservationModule\Model\ReservationService;
use App\UserModule\Controls\ListUser\IListUserControlFactory;
use App\UserModule\Controls\ListUser\ListUserControl;
use App\UserModule\Model\UserService;
use Nette\Security\User;

final class ReservationListAttendantPresenter extends SecurePresenter
{
    private IListReservationAttendantControlFactory $_listReservationAttendantFactory;
    private User $_user;
    private ReservationService $_reservationService;

    public function __construct(
        IListReservationAttendantControlFactory $listReservationAttendantFactory,
        User $user,
        ReservationService $reservationService
    )
    {
        parent::__construct();
        $this->_listReservationAttendantFactory = $listReservationAttendantFactory;
        $this->_user = $user;
        $this->_reservationService = $reservationService;
    }
    protected function createComponentListReservationAttendant(): ListReservationAttendantControl
    {
        return $this->_listReservationAttendantFactory->create();
    }
    public function renderDefault(): void
    {
        // Add any setup required for rendering the default view.
    }
}