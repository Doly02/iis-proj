<?php

declare(strict_types=1);

namespace App\ConferenceModule\Controls\ListConferenceCreator;

use App\CommonModule\Controls\DataGrid\DataGridControl;
use App\CommonModule\Controls\DataGrid\IDataGridControlFactory;
use App\ConferenceModule\Model\ConferenceService;
use App\ReservationModule\Model\ReservationService;
use Nette\Application\UI\Control;
use Nette\Database\Table\Selection;
use Nette\Security\User;
use App\CommonModule\Controls\DataGrid;
final class ListConferenceCreatorControl extends Control
{
    private ConferenceService $conferenceService;
    private ReservationService $reservationService;
    private User $user;
    private $dataGridControlFactory;
    private ?Selection $userConferences = null;

    public function __construct(
        User $user,
        ConferenceService $conferenceService,
        ReservationService $reservationService,
        IDataGridControlFactory $dataGridControlFactory
    ) {
        $this->user = $user;
        $this->conferenceService = $conferenceService;
        $this->reservationService = $reservationService;
        $this->dataGridControlFactory = $dataGridControlFactory;

    }

    protected function createComponentListConferenceCreator() : DataGridControl
    {
        $grid = $this->dataGridControlFactory->create($this->conferenceService, function ($selection) {
            $selection->where('organiser_id', $this->user->getId());
        });

        $grid->setPagination(false);
        $grid->removeColumn('id');

        $grid->addColumnText('name', 'Conference Name', 'name')
            ->setFilterText();

        $grid->addColumnText('area_of_interest', 'Area of Interest', 'area_of_interest')
            ->setFilterText();

        $grid->addColumnDateTime('start_time', 'Starts', 'start_time')
            ->setFormat('d.m.Y H:i:s')
            ->setFilterDate();

        $grid->addColumnDateTime('end_time', 'Ends', 'end_time')
            ->setFormat('d.m.Y H:i:s')
            ->setFilterDate();

        $grid->addColumnText('price', 'Price (Kč)', 'price')
            ->setFilterRange();

        $grid->addColumnText('capacity', 'Capacity', 'capacity')
            ->setFilterText();

        $grid->addAction('detail', '', 'ConferenceDetail:default')
            ->setTitle('Show detail')
            ->setclass('')
            ->setIcon('eye');

        return $grid;
    }

    public function defaultFilter(Selection $selection) : void
    {
        $identity = $this->user->getIdentity();
        if ($identity instanceof \Nette\Security\Identity)
        {
            $selection->where('organizer_id', $identity->id);
        }
    }
    public function render() : void
    {
        $this->template->setFile(__DIR__ . '/../../templates/ConferenceListCreator/list.latte');
        $this->template->render();
    }
}