<?php

declare(strict_types=1);

namespace App\CommonModule\Presenters;

use Nette\Application\UI\Presenter;
use Nette\Database\Explorer;

abstract class BasePresenter extends Presenter
{
    protected Explorer $database;

    public function __construct(Explorer $database)
    {
        parent::__construct(); // Calling parent constructor
        $this->database = $database;
    }

    public function beforeRender(): void
    {
        parent::beforeRender();
        $this->template->title = $this->template->title ?? "Default Title";
        $this->template->basePath = $this->getHttpRequest()->getUrl()->getBasePath();
        $this->setLayout(__DIR__ . '/../../UI/@layout.latte');
        dump($this->getTemplate()->getFile()); // Listing of the current template
    }
}
