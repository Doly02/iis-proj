<?php

declare(strict_types=1);

namespace App\PresentationModule\Controls\LecturerListPresentation;

use Nette\Application\UI\Control;
use Nette\Database\Table\ActiveRow;
use Nette\Security\User;
use Ublaboo\DataGrid\DataGrid;
use App\PresentationModule\Model\PresentationService;
use Ublaboo\DataGrid\Exception\DataGridException;

final class LecturerListPresentationControl extends Control
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
    protected function createComponentLecturerPresentationGrid(): DataGrid
    {
        $grid = new DataGrid;

        $grid->setDataSource($this->PresentationService->getLecturerPresentations($this->user->getId()));

        $grid->setRefreshUrl(false);
        $grid->setAutoSubmit(false);
        $grid->setRememberState(false);


        $grid->setDefaultPerPage(20);
        $grid->setPagination(false);

        $grid->addColumnText('conference_name', 'Conference')
            ->setSortable()
            ->setFilterText()
            ->setPlaceholder('Search by name');

        $grid->addColumnText('name', 'Name')
            ->setSortable()
            ->setFilterText()
            ->setPlaceholder('Search by name');

        $grid->addColumnText('state', 'State')
            ->setSortable();

        $grid->addAction('detail', '', 'LecturerPresentationDetail:default')
            ->setTitle('Show detail')
            ->setclass('')
            ->setIcon('edit');

        return $grid;
    }


    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/../../templates/LecturerPresentationList/list.latte');
        $this->template->render();
    }
}
