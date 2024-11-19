<?php

namespace App\CommonModule\Controls\SideBar;

use Nette\Application\UI\Control;

final class SideBarControl extends Control
{
    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/../../templates/SideBar/sideBar.latte');
        $this->template->render();
    }

}