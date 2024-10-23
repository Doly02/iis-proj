<?php

declare(strict_types=1);

namespace App\CommonModule\Presenters;

use Nette\Application\UI\Presenter;
use App\UserModule\Model\Action;

use Nette\Caching\Cache;
use Nette\Caching\Storages\FileStorage;

abstract class SecurePresenter extends BasePresenter
{
    public function startup() : void
    {
        parent::startup();
        if (!$this->isPublicPage())
        {
            $this->checkLoggedIn();
        }
    }

    protected function getModuleName(): string
    {
        /* Get Name Of Presenter And Extraction of Module */
        if (\preg_match('~^(\w+):~', (string) $this->getName(), $matches))
        {
            return \strtolower($matches[1]);
        }
        return '';  /* Module Not Found */
    }

    protected function getPresenterName(): string
    {
        /* Get Name Of Presenter And Extraction of Module */
        if (\preg_match('~:(\w+)\z~', (string) $this->getName(), $matches))
        {
            return \strtolower($matches[1]);
        }
        return '';  /* Presenter Not Found */
    }
    /**
     * A method to determine if the current page is public and does not require a login.
     */
    protected function isPublicPage(): bool
    {
        // Here you can add conditions that decide whether the page is public.
        // For example, access to the homepage does not require a login:
        return $this->getName() === 'Home';

    }

    public function checkPrivilege(?string $resource = null, ?action $privilege = null) : bool
    {
        /* Default Action, If Privilege Is Not Defined */
        $privilege = $privilege ?? $this->getAction();

        /* Use Default Source Based On Module & Presenter If Resource Is Not Defined */
        $resource = $resource ?? $this->getModuleName() . '.' . $this->getPresenterName();

        /* Check If User Has Privileges For This Source And Action */
        if (false === $this->getUser()->isAllowed($resource, $privilege))
        {
            /* Permission Denied */
            $this->flashMessage(sprintf('Permission Denied To "%s" on Resource "%s".', $privilege, $resource), 'error');
            $this->redirect(':Common:Home:default');
        }
        return true;
    }
    protected function checkLoggedIn() : void
    {
        $user = $this->getUser();
        $session = $this->getSession('user_activity');


        /* If User Is Not Logged In */
        /*
        if (!$user->isLoggedIn())
        {
            $this->flashMessage("You have been logged out due to inactivity.", 'info');
            $this->redirect(':UserModule:Authentication:signIn', [
                'login-backLink' => $this->storeRequest()
            ]);
        }
        */

        /* Check of Unactivity -> If User Has No Activity Durring 30 Minutes Then He/She Will Be Logged Out
         * 30 min. == 1800 sec.
         */
        if (isset($session->lastActivity) && (time() - $session->lastActivity > 1800))
        {
            $this->getUser()->logout();
            $this->flashMessage("You have been logged out due to inactivity.", 'info');

            $storage = new FileStorage(__DIR__ . '/../../../temp/cache'); // Úprava cesty dle vaší aplikace
            $cache = new Cache($storage);
            $cache->clean([Cache::ALL => true]); // Vymazání veškeré cache

            $this->redirect(':CommonModule:Home:default');
        }

        // TODO: Store The Time of Activity
        $session->lastActivity = time();
    }
}