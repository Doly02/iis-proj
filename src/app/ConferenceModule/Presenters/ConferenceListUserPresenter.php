<?php

namespace App\ConferenceModule\Presenters;

use App\CommonModule\Presenters\SecurePresenter;
use App\ConferenceModule\Controls\ListConferenceUser;
use App\ConferenceModule\Controls\ListConferenceUser\IListConferenceUserControlFactory;
use App\ConferenceModule\Controls\ListConferenceUser\ListConferenceUserControl;
use App\ReservationModule\Model\ReservationService;
use App\TicketModule\Model\TicketService;
use Nette\Security\User;

final class ConferenceListUserPresenter extends SecurePresenter
{
    private IListConferenceUserControlFactory $_listConferenceUserControlFactory;
    private ReservationService $_reservationService;
    private User $_user;

    public function __construct(
        IListConferenceUserControlFactory $listConferenceUserControlFactory,
        ReservationService $reservationService,
        User $user
    )
    {
        parent::__construct();
        $this->_listConferenceUserControlFactory = $listConferenceUserControlFactory;
        $this->_reservationService = $reservationService;
        $this->_user = $user;
    }


    protected function createComponentConferenceGrid(): ListConferenceUserControl
    {
        // Získání ID uživatele
        $userId = $this->_user->getId();
        if (!$userId) {
            $this->flashMessage('User is not logged in.', 'error');
            $this->redirect(':UserModule:Auth:signIn');
        }

        // Načtení kontroleru
        $control = $this->_listConferenceUserControlFactory->create();
        $control->loadUserConferences(); // Ujistěte se, že konference jsou načteny
        return $control;
    }

    public function renderDefault(): void
    {

    }
}