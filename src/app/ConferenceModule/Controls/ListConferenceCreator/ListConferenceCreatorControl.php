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
use Nette\Utils\Html;
use Ublaboo\DataGrid\DataSource\ArrayDataSource;

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
        $organiserId = $this->user->getId();
        $data = $this->conferenceService->getConferencesWithCapacityByOrganiser($organiserId);

        $grid = $this->dataGridControlFactory->create($this->conferenceService, function ($selection) use ($organiserId) {
            $selection->where('organiser_id', $organiserId);
        });

        $grid->setDataSource(new ArrayDataSource($data));

        $grid->setAutoSubmit(false);

        $grid->setPagination(false);
        $grid->removeColumn('id');

        $grid->addColumnText('name', 'Conference Name', 'name')
            ->setFilterText()
            ->setCondition(function ($data, $value) {
                return array_filter($data, function ($row) use ($value) {
                    return stripos($row['name'], $value) !== false;
                });
            });

        $grid->addColumnText('area_of_interest', 'Area of Interest', 'area_of_interest')
            ->setFilterText()
            ->setCondition(function ($data, $value) {
                return array_filter($data, function ($row) use ($value) {
                    return stripos($row['area_of_interest'], $value) !== false;
                });
            });

        $grid->addColumnDateTime('start_time', 'Starts', 'start_time')
            ->setFormat('d.m.Y H:i')
            ->setFilterDate();

        $grid->addColumnDateTime('end_time', 'Ends', 'end_time')
            ->setFormat('d.m.Y H:i')
            ->setFilterDate();

        $grid->addColumnText('price', 'Price (Kč)', 'price')
            ->setFilterRange();

        $grid->addColumnText('capacity', 'Total capacity')
            ->setRenderer(function ($row) {
                $html = Html::el('span')
                    ->setHtml(sprintf('%d / <b>%d</b>', $row['occupied_capacity'], $row['capacity']));
                return $html;
            })
            ->setSortable()
            ->setFilterSelect([
                '' => 'All',
                'full' => 'Full capacity',
                'free' => 'Available',
            ], 'Availability')
            ->setCondition(function ($data, $value) {
                if ($value === 'full') {
                    return array_filter($data, function ($row) {
                        return $row['occupied_capacity'] === $row['capacity'];
                    });
                } elseif ($value === 'free') {
                    return array_filter($data, function ($row) {
                        return $row['occupied_capacity'] < $row['capacity'];
                    });
                }
                return $data;
            });

        $grid->addAction('detail', '', 'ConferenceCreatorDetail:defaultOrganizer')
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