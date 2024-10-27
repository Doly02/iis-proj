<?php

declare(strict_types=1);

namespace App\CommonModule\Presenters;

use Nette\Application\UI\Presenter;

abstract class BasePresenter extends Presenter
{
    public function startup(): void
    {
        parent::startup();
        /* Launch Session */
        $this->getSession()->start();

        /* Setup of Session */
        $sessionSection = $this->getSession('user_activity');
        if (!isset($sessionSection->lastActivity))
        {
            $sessionSection->lastActivity = time(); //<! Time of Last Activity
        }
    }

    /* TODO: Use For CSS Style
    public function beforeRender() : void
    {
        parent::beforeRender();
        $this->template->basePath = $this->getHttpRequest()->getUrl()->getBasePath();
        $this->setLayout(__DIR__ . '/../../../templates/@layout.latte');
        dump($this->getTemplate()->getFile()); // Log of Actual Template

    }*/

    public function handleSignOut(?string $redirect = ':UserModule:Authentication:signIn') : void
    {
        $this->getUser()->logout(true);
        $this->flashMessage('You Have Been Logged Out.', 'info');
        $this->redirect($redirect);
    }
}