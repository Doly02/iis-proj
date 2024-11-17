<?php

declare(strict_types=1);

namespace App\ConferenceModule\Controls\ListConferenceUser;

use App\ConferenceModule\Model\ConferenceService;
use App\ReservationModule\Model\ReservationService;
use Nette\Application\UI\Control;
use Nette\Database\Table\Selection;
use Nette\Security\User;
use Ublaboo\DataGrid\DataGrid;
use Ublaboo\DataGrid\Exception\DataGridException;

final class ListConferenceUserControl extends Control
{
    private ConferenceService $conferenceService;
    private ReservationService $reservationService;
    private User $user;
    private ?Selection $userConferences = null;

    public function __construct(
        User $user,
        ConferenceService $conferenceService,
        ReservationService $reservationService
    ) {
        $this->user = $user;
        $this->conferenceService = $conferenceService;
        $this->reservationService = $reservationService;

    }

    /**
     * Set User's Conferences for the DataGrid
     */
    public function loadUserConferences(): void
    {
        $userId = $this->user->getId();
        if (!$userId) {
            throw new \Exception('User is not logged in.');
        }

        \Tracy\Debugger::log("User ID: " . $userId, 'info');

        // Get reserved conference IDs for the user
        $reservedConferenceIds = $this->reservationService
            ->getUserReservedConferences($userId)
            ->fetchPairs(null, 'conference_id');

        \Tracy\Debugger::log("Reserved Conference IDs: " . json_encode($reservedConferenceIds), 'info');

        // Load conferences by their IDs
        $this->userConferences = $this->conferenceService
            ->getConferenceTable()
            ->where('id', $reservedConferenceIds);

        if ($this->userConferences->count() === 0) {
            \Tracy\Debugger::log("No conferences found for user ID: " . $userId, 'warning');
        } else {
            \Tracy\Debugger::log("Loaded " . $this->userConferences->count() . " conferences for user ID: " . $userId, 'info');
        }
    }

    /**
     * @throws DataGridException
     */
    protected function createComponentConferenceGrid(): DataGrid
    {
        if ($this->userConferences === null) {
            $this->loadUserConferences();
        }
        \Tracy\Debugger::log("createComponentConferenceGrid", 'info');
        $grid = new DataGrid();
        $grid->setDataSource($this->userConferences);

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

        $grid->addAction('detail', '', 'ConferenceDetail:default')
            ->setTitle('Show detail')
            ->setclass('')
            ->setIcon('eye');

        return $grid;
    }

    public function render(): void
    {
        if ($this->userConferences === null) {
            $this->loadUserConferences();
        }
        $this->template->userConferences = $this->userConferences;
        $this->template->setFile(__DIR__ . '/../../templates/ConferenceListUser/list.latte');
        $this->template->render();
    }
}