<?php

namespace App\CommonModule\Controls\SideBar;

use Nette\Application\UI\Control;

final class SideBarControl extends Control
{
    private string $accountType;

    public function __construct(string $accountType)
    {
        $this->accountType = $accountType;
    }
    public function render(): void
    {
        $this->template->accountType = $this->accountType;
        $this->template->setFile(__DIR__ . '/../../templates/SideBar/sideBar.latte');
        $this->template->render();
    }

}