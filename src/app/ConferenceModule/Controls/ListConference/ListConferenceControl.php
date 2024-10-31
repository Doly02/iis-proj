<?php

declare(strict_types=1);

namespace App\ConferenceModule\Controls\ListConference;

use Nette\Application\UI\Control;
use Nette\Security\User;
use Ublaboo\DataGrid\DataGrid;
use App\ConferenceModule\Model\ConferenceService;

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

    protected function createComponentConferenceGrid(): DataGrid
    {
        $grid = new DataGrid;
        $grid->setDataSource($this->conferenceService->getConferenceTable());

        $grid->addColumnText('name', 'Name')
            ->setSortable();

        $grid->addColumnText('start_time', 'Start')
            ->setSortable();

        $grid->addColumnText('price', 'Price (kč)')
            ->setSortable();

        $grid->addColumnText('capacity', 'Capacity')
            ->setSortable();

        return $grid;
    }

    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/../../templates/ConferenceList/list.latte');
        $this->template->render();
    }
}
