<?php

declare(strict_types=1);

namespace App\CommonModule\Controls\DataGrid;

use App\CommonModule\Model\BaseService;

final class DataGridControl extends \Ublaboo\DataGrid\DataGrid
{
    public function __construct(BaseService $service, callable $selectionCallback = null)
    {
        parent::__construct();

        $this->setDataSource($service->selectionCallback($selectionCallback));
        $this->setRememberState(false);

        $this->addColumnNumber('id', 'ID')
            ->setFormat(0, ',', '')
            ->setAlign('left')
            ->setFilterText();
    }
}
