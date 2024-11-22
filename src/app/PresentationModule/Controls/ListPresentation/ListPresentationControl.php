<?php

declare(strict_types=1);

namespace App\PresentationModule\Controls\ListPresentation;

use Nette\Application\UI\Control;
use Nette\Database\Table\ActiveRow;
use Nette\Security\User;
use Ublaboo\DataGrid\DataGrid;
use App\PresentationModule\Model\PresentationService;
use Ublaboo\DataGrid\Exception\DataGridException;

final class ListPresentationControl extends Control
{
    /** @var User */
    private $user;
    private PresentationService $PresentationService;
    private ?int $conferenceId = null;

    public function __construct(User $user, PresentationService $PresentationService)
    {
        $this->user = $user;
        $this->PresentationService = $PresentationService;
    }

    public function setConferenceId(int $conferenceId): void
    {
        $this->conferenceId = $conferenceId;
    }

    /**
     * @throws DataGridException
     */
    protected function createComponentPresentationGrid(): DataGrid
    {
        $grid = new DataGrid;
        if($this->conferenceId)
            $grid->setDataSource($this->PresentationService->getConferencePresentations($this->conferenceId));
        else
            $grid->setDataSource($this->PresentationService->getOrganizerPresentations($this->user->getId()));

        $grid->setRefreshUrl(false);
        $grid->setAutoSubmit(false);
        $grid->setRememberState(false);


        $grid->setDefaultPerPage(20);
        $grid->setPagination(false);

        $grid->addColumnText('conference_name', 'Conference')
            ->setSortable()
            ->setFilterText()
            ->setCondition(function ($data, $value) {
                return array_filter($data, function ($row) use ($value) {
                    return stripos($row['conference_name'], $value) !== false;
                });
            })
            ->setPlaceholder('Search by name');

        $grid->addColumnText('name', 'Name')
            ->setSortable()
            ->setFilterText()
            ->setCondition(function ($data, $value) {
                return array_filter($data, function ($row) use ($value) {
                    return stripos($row['name'], $value) !== false;
                });
            })
            ->setPlaceholder('Search by name');

        $grid->addColumnText('state', 'State')
            ->setSortable();

        $grid->addAction('detail', '', 'PresentationDetail:default')
            ->setTitle('Show detail')
            ->setclass('')
            ->setIcon('eye');

        return $grid;
    }


    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/../../templates/PresentationList/list.latte');
        $this->template->render();
    }
}
