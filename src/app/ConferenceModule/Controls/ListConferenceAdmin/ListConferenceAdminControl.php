<?php

declare(strict_types=1);

namespace App\ConferenceModule\Controls\ListConferenceAdmin;

use App\ConferenceModule\Model\ConferenceService;
use App\ReservationModule\Model\ReservationService;
use Nette\Application\UI\Control;
use Nette\Database\Table\Selection;
use Nette\Security\User;
use Nette\Utils\Html;
use Ublaboo\DataGrid\DataGrid;
use Ublaboo\DataGrid\DataSource\ArrayDataSource;
use Ublaboo\DataGrid\Exception\DataGridException;

final class ListConferenceAdminControl extends Control
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
    protected function createComponentConferenceAdminGrid() : DataGrid
    {
        $grid = new DataGrid;

        $grid->setDataSource(new ArrayDataSource($this->conferenceService->getConferenceTableWithCapacity()));

        $grid->setRefreshUrl(false);
        $grid->setAutoSubmit(false);
        $grid->setRememberState(false);

        $grid->setDefaultPerPage(20);
        $grid->setPagination(false);

        // Sloupec: Jméno
        $grid->addColumnText('name', 'Name')
            ->setSortable()
            ->setFilterText()
            ->setCondition(function ($data, $value) {
                return array_filter($data, function ($row) use ($value) {
                    return stripos($row['name'], $value) !== false;
                });
            })
            ->setPlaceholder('Search by name');

        $grid->addColumnText('area_of_interest', 'Area of Interest')
            ->setFilterText()
            ->setCondition(function ($data, $value) {
                return array_filter($data, function ($row) use ($value) {
                    return stripos($row['area_of_interest'], $value) !== false;
                });
            })
            ->setPlaceholder('Search by interest');

        $grid->addColumnDateTime('start_time', 'Start')
            ->setFormat('d.m.Y H:i')
            ->setSortable()
            ->setFilterDateRange();

        $grid->addColumnText('price', 'Price (kč)')
            ->setSortable()
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

        $grid->addAction('edit', 'Edit', ':ConferenceModule:EditConference:edit')
            ->setIcon('edit')
            ->setClass('btn btn-sm btn-primary')
            ->setTitle('Edit Conference')
            ->setRenderer(function ($row) {
                $link = $this->getPresenter()->link(':ConferenceModule:EditConference:edit', ['id' => $row['id']]);
                return Html::el('a')
                    ->href($link)
                    ->setHtml('Edit')
                    ->setAttribute('class', 'btn btn-sm btn-primary');
            });

        return $grid;
    }

    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/../../templates/ConferenceListAdmin/list.latte');
        $this->template->render();
    }
}