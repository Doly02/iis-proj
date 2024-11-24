<?php

namespace App\ReservationModule\Presenters;

use App\CommonModule\Presenters\SecurePresenter;
use App\ReservationModule\Controls\ListReservationOrganizer\IListReservationOrganizerControlFactory;
use App\ReservationModule\Controls\ListReservationOrganizer\ListReservationOrganizerControl;
use App\ReservationModule\Model\ReservationService;
use App\UserModule\Controls\ListUser\IListUserControlFactory;
use App\UserModule\Controls\ListUser\ListUserControl;
use App\UserModule\Model\UserService;
use Nette\Security\User;

final class ReservationListOrganizerPresenter extends SecurePresenter
{
    private IListReservationOrganizerControlFactory $listReservationOrganizerFactory;
    private User $_user;
    private ReservationService $_reservationService;

    public function __construct(
        IListReservationOrganizerControlFactory $listReservationAttendantFactory,
        User $user,
        ReservationService $reservationService
    )
    {
        parent::__construct();
        $this->listReservationOrganizerFactory = $listReservationAttendantFactory;
        $this->_user = $user;
        $this->_reservationService = $reservationService;
    }
    protected function createComponentListReservationOrganizer(): ListReservationOrganizerControl
    {
        return $this->listReservationOrganizerFactory->create();
    }
    public function renderDefault(): void
    {
        // Add any setup required for rendering the default view.
    }
}