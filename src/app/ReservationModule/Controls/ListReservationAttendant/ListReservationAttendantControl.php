<?php

declare(strict_types=1);

namespace App\ReservationModule\Controls\ListReservationAttendant;

use App\CommonModule\Controls\DataGrid\DataGridControl;
use App\CommonModule\Controls\DataGrid\IDataGridControlFactory;
use App\ReservationModule\Model\ReservationService;
use Ublaboo\DataGrid\Column\Action\Confirmation\StringConfirmation;
use App\RoomModule\Model\RoomService;
use Nette\Application\UI\Control;
use Nette\Database\Table\Selection;
use Nette\Security\User;
use Ublaboo\DataGrid\DataGrid;

final class ListReservationAttendantControl extends Control
{
    private ReservationService $_reservationService;
    private $_dataGridControlFactory;

    private $_user;
    private ?Selection $userConferences = null;

    public function __construct(
        User $user,
        ReservationService $reservationService,
        IDataGridControlFactory $dataGridControlFactory
    ) {
        $this->_user = $user;
        $this->_reservationService = $reservationService;
        $this->_dataGridControlFactory = $dataGridControlFactory;
    }

    protected function createComponentListReservationAttendant(): DataGrid
    {
        $grid = $this->_dataGridControlFactory->create($this->_reservationService);
        $reservations = $this->_reservationService->getUserReservations($this->_user->getId());

        $grid->setDataSource($reservations);
        $grid->setPagination(false);

        $grid->addColumnText('conference_name', 'Conference Name')
            ->setSortable();

        $grid->addColumnText('created_date', 'Reservation Date')
            ->setSortable();

        $grid->addColumnText('num_reserved_tickets', 'Tickets Reserved')
            ->setSortable();

        $grid->addColumnText('price_to_pay', 'Total Price')
            ->setSortable();

        $grid->addAction('detail', '', ':ReservationModule:ReservationDetail:default', ['id' => 'id'])
            ->setIcon('ticket')
            ->setClass('')
            ->setTitle('Show Reservation');

        // Cancel Reservation
        /*
        $grid->addAction('cancel', 'Cancel Reservation', 'cancel!')
            ->setIcon('trash')
            ->setClass('btn btn-danger btn-sm')
            ->setConfirmation(
                new StringConfirmation('Are you sure you want to cancel this reservation?')
            );
        */
        return $grid;
    }

    public function render() : void
    {
        $this->template->setFile(__DIR__ . '/../../templates/ReservationListAttendant/list.latte');
        $this->template->render();
    }
}
