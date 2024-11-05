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

    /**
     * @throws DataGridException
     */
    /*protected function createComponentReservationGrid(): DataGrid
    {
        $grid = new DataGrid;
        $grid->setDataSource($this->reservationService->getTable());

        $grid->setRefreshUrl(false);
        $grid->setAutoSubmit(false);
        $grid->setRememberState(false);


        $grid->setDefaultPerPage(20);
        $grid->setPagination(false);


        $grid->addColumnText('name', 'Name')
            ->setSortable()
            ->setFilterText()
            ->setPlaceholder('Search by name');


        $grid->addColumnDateTime('start_time', 'Start')
            ->setFormat('d.m.Y H:i:s')
            ->setSortable()
            ->setFilterDateRange();

        $grid->addColumnText('price', 'Price (kč)')
            ->setSortable()
            ->setFilterRange();

        $grid->addColumnText('capacity', 'Capacity')
            ->setSortable();

        $grid->addAction('detail', '', 'ReservationDetail:default')
            ->setTitle('Show detail')
            ->setclass('')
            ->setIcon('eye');

        return $grid;
    }*/


    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/../../templates/ReservationList/list.latte');
        $this->template->render();
    }
}
