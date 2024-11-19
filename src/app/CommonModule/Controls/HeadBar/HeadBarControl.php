<?php

namespace App\CommonModule\Controls\HeadBar;

use Nette\Application\UI\Control;

final class HeadBarControl extends Control
{
    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/../../templates/HeadBar/headBar.latte');
        $this->template->render();
    }
}