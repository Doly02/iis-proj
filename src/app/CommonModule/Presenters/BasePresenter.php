<?php

declare(strict_types=1);

namespace App\CommonModule\Presenters;

use Nette\Application\UI\Presenter;

abstract class BasePresenter extends Presenter
{
    protected string $accountType = 'unknown';
    public function startup(): void
    {
        parent::startup();
        /* Launch Session */
        $this->getSession()->start();

        $accountTypeCookie = $this->getHttpRequest()->getCookie('account_type');
        if ($accountTypeCookie)
        {
            if ($accountTypeCookie === "6409gj0wehfpj20c2j-9u420jv3rh09vuj2c0j02efpjdfsjfpsdovc2e9")
            {
                $this->accountType = "user";
            }
            else if ($accountTypeCookie === "roihsdvds0icoqh0cjwe0g8vbv430wt70r0qe9r0eyvhvwv8efh20ciewf")
            {
                $this->accountType = "admn";
            }
        }

        $this->template->accountType = $this->accountType;
        /* Setup of Session */
        $sessionSection = $this->getSession('user_activity');
        if (!isset($sessionSection->lastActivity))
        {
            $sessionSection->lastActivity = time(); //<! Time of Last Activity
        }
    }

    public function beforeRender(): void
    {
        parent::beforeRender();
        $this->template->basePath = $this->getHttpRequest()->getUrl()->getBasePath();
        $this->setLayout(__DIR__ . '/../../UI/@layout.latte');
    }


    public function handleSignOut(?string $redirect = ':UserModule:Authentication:signIn') : void
    {
        $this->getUser()->logout(true);
        $this->flashMessage('You Have Been Logged Out.', 'info');
        $this->redirect($redirect);
    }
}