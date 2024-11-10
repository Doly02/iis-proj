<?php

namespace App\ConferenceModule\Controls\ListConference;

use Nette\Application\UI\Control;
use Nette\Database\Table\ActiveRow;
use Nette\Security\User;
use Ublaboo\DataGrid\DataGrid;
use App\ConferenceModule\Model\ConferenceService;
use Ublaboo\DataGrid\Exception\DataGridException;

final class ListConferenceControl extends Control
{
    /** @var User */
    private $user;
    private ConferenceService $conferenceService;

    public function __construct(User $user, ConferenceService $conferenceService)
    {
        $this->user = $user;
        $this->conferenceService = $conferenceService;
    }

    /**
     * @throws DataGridException
     */
    protected function createComponentConferenceGrid(): DataGrid
    {
        $grid = new DataGrid;
        $grid->setDataSource($this->conferenceService->getConferenceTable());

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

        $grid->addAction('detail', '', 'ConferenceDetail:default')
            ->setTitle('Show detail')
            ->setclass('')
            ->setIcon('eye');

        return $grid;
    }


    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/../../templates/ConferenceList/list.latte');
        $this->template->render();
    }
}
