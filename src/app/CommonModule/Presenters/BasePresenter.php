<?php

declare(strict_types=1);

namespace App\CommonModule\Presenters;

use Nette\Application\UI\Presenter;
use Nette\Database\Explorer; // Importing the correct class

abstract class BasePresenter extends Presenter
{
    private Explorer $database;

    public function __construct(Explorer $database)
    {
        parent::__construct(); // Calling parent constructor
        $this->database = $database;
    }


    public function beforeRender(): void
    {
        parent::beforeRender();
        $this->template->basePath = $this->getHttpRequest()->getUrl()->getBasePath();
        $this->setLayout(__DIR__ . '/../../../templates/@layout.latte');
        dump($this->getTemplate()->getFile()); // Listing of the current template
    }
}

