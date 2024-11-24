<?php

namespace App\CommonModule\Controls\Footer;

use Nette\Application\UI\Control;

final class FooterControl extends Control
{
    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/../../templates/Footer/footer.latte');
        $this->template->render();
    }
}