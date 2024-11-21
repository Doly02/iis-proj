<?php

declare(strict_types=1);

namespace App\ReservationModule\Controls\ListReservationOrganizer;

use App\CommonModule\Controls\DataGrid\DataGridControl;
use App\CommonModule\Controls\DataGrid\IDataGridControlFactory;
use App\ReservationModule\Model\ReservationService;
use Ublaboo\DataGrid\Column\Action\Confirmation\StringConfirmation;
use App\RoomModule\Model\RoomService;
use Nette\Application\UI\Control;
use Nette\Database\Table\Selection;
use Nette\Security\User;
use Ublaboo\DataGrid\DataGrid;

final class ListReservationOrganizerControl extends Control
{
    private ReservationService $_reservationService;
    private IDataGridControlFactory $_dataGridControlFactory;

    private User $_user;
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

    protected function createComponentListReservationOrganizer(): DataGrid
    {
        $grid = $this->_dataGridControlFactory->create($this->_reservationService);

        // Získejte ID konference z parametru
        $conferenceId = $this->getPresenter()->getParameter('conferenceId');

        // Načtěte rezervace pro danou konferenci
        $reservations = $this->_reservationService->getReservationsByConferenceId((int)$conferenceId);

        $grid->setDataSource($reservations);
        $grid->setPagination(false);

        // Sloupce datagridu
        $grid->addColumnText('customer_name', 'Customer Name')
            ->setRenderer(function ($item) {
                return $item->first_name . ' ' . $item->last_name;
            });

        $grid->addColumnText('conference_name', 'Conference Name')
            ->setSortable();

        $grid->addColumnText('created_date', 'Reservation Date')
            ->setSortable();

        $grid->addColumnText('num_reserved_tickets', 'Tickets Reserved')
            ->setSortable();

        $grid->addColumnText('price_to_pay', 'Total Price')
            ->setSortable();

        $grid->addColumnText('is_paid', 'Paid Status')
            ->setRenderer(function ($item) {
                return $item->is_paid ? 'Yes' : 'No';
            })
            ->setSortable();

        // Approve Reservation
        $grid->addAction('approve', 'Approve', 'approve!')
            ->setIcon('check')
            ->setClass('btn btn-success btn-sm')
            ->setConfirmation(
                new StringConfirmation('Are you sure you want to approve this reservation?')
            );

        return $grid;
    }
    public function handleApprove(int $id): void
    {
        $this->_reservationService->approveReservation($id);

        $this->getPresenter()->flashMessage('Reservation approved successfully.', 'success');
        $this->redirect('this');
    }

    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/../../templates/ReservationListOrganizer/list.latte');
        $this->template->render();
    }
}