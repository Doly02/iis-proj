<?php

declare(strict_types=1);

namespace App\ReservationModule\Controls\ListReservation;

use Nette\Application\UI\Control;
use Nette\Database\Table\ActiveRow;
use Nette\Security\User;
use Ublaboo\DataGrid\DataGrid;
use App\ReservationModule\Model\ReservationService;
use Ublaboo\DataGrid\Exception\DataGridException;

final class ListReservationControl extends Control
{
    /** @var User */
    private $user;
    private ReservationService $reservationService;

    public function __construct(User $user, ReservationService $reservationService)
    {
        $this->user = $user;
        $this->reservationService = $reservationService;
    }

    protected function createComponentReservationGrid(): DataGrid
    {
        $grid = new DataGrid;
        $userId = $this->user->getId();
        $grid->setDataSource($this->reservationService->getTableWithConferenceName($userId));

        $grid->setRefreshUrl(false);
        $grid->setAutoSubmit(false);
        $grid->setRememberState(false);


        $grid->setDefaultPerPage(20);
        $grid->setPagination(false);


        $grid->addColumnText('conference_name', 'Conference name')
            ->setSortable()
            ->setFilterText()
            ->setPlaceholder('Search by conference');

        // TODO reservation needs datetime created


        $grid->addColumnText('price_to_pay', 'Total price (kč)')
            ->setSortable()
            ->setFilterRange();

        $grid->addColumnText('num_reserved_tickets', 'Tickets reserved')
            ->setSortable()
            ->setFilterRange();

        $grid->addAction('detail', '', 'ReservationDetail:default')
            ->setTitle('Show detail')
            ->setclass('')
            ->setIcon('eye');

        return $grid;
    }


    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/../../templates/ReservationList/list.latte');
        $this->template->render();
    }
}
