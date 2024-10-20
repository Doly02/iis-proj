<?php

declare(strict_types=1);

namespace App\CommonModule\Presenters;

use Nette\Application\UI\Presenter;

abstract class BasePresenter extends Presenter
{
    public function beforeRender(): void
    {
        parent::beforeRender();
        $this->template->basePath = $this->getHttpRequest()->getUrl()->getBasePath();
        $this->setLayout(__DIR__ . '/../../../templates/@layout.latte');
        dump($this->getTemplate()->getFile()); // Výpis aktuální šablony

    }
}
