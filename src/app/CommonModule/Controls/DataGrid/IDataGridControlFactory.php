<?php

declare(strict_types=1);

namespace App\CommonModule\Controls\DataGrid;

use App\CommonModule\Model\BaseService;

interface IDataGridControlFactory
{
    function create(
        BaseService $service,
        callable $selectionCallback = null
    ): DataGridControl;
}
