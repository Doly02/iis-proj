<?php

declare(strict_types=1);

namespace App\CommonModule\Presenters;

use App\UserModule\Enums\Action;
use App\UserModule\Model\UserService;
use Nette\Application\UI\Presenter;

use Nette\Caching\Cache;
use Nette\Caching\Storages\FileStorage;
use Tracy\Debugger;

abstract class SecurePresenter extends BasePresenter
{
    private ?UserService $userService = null;

    public function startup() : void
    {

        parent::startup();
        Debugger::log("Reached startup", 'info');
        if (!$this->isPublicPage())
        {
            $this->checkLoggedIn();
        }

        // Předání account_type šabloně
        $this->template->accountType = $this->accountType;
    }

    protected function getModuleName(): string
    {
        Debugger::log("Reached getModuleName", 'info');
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
        Debugger::log("Reached isPublicPage", 'info');
        // Getting a list of public sites from the configuration
        $publicPages = $this->context->parameters['publicPages'] ?? [];

        // Getting the current presenter and event name
        $currentPage = $this->getName() . ':' . $this->getAction();

        // Check if the current page is in the public list
        return in_array($currentPage, $publicPages, true);
    }

    public function checkPrivilege(?string $resource = null, ?string $privilege = null) : bool
    {
        Debugger::log("Reached checkPrivilege", 'info');
        /* Default Action, If Privilege Is Not Defined */
        $privilege = $privilege ?? $this->getAction();

        /* Use Default Source Based On Module & Presenter If Resource Is Not Defined */
        $resource = $resource ?? $this->getModuleName() . '.' . $this->getPresenterName();

        Debugger::log("Checking privilege for resource: $resource and action: $privilege", Debugger::INFO);

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

        Debugger::log("Checking if user is logged in.", Debugger::INFO);

        /* Check of Unactivity -> If User Has No Activity Durring 30 Minutes Then He/She Will Be Logged Out
         * 30 min. == 1800 sec.
         */
        \Tracy\Debugger::log('Sec. User activity recorded at: ' . date('Y-m-d H:i:s', $session->lastActivity), \Tracy\ILogger::INFO);

        if (isset($session->lastActivity) && (time() - $session->lastActivity > 1800))
        {
            Debugger::log("User has been logged out due to inactivity.", Debugger::INFO);
            $this->getUser()->logout();
            $this->flashMessage("You have been logged out due to inactivity.", 'info');

            $storage = new FileStorage(__DIR__ . '/../../../temp/cache'); // Úprava cesty dle vaší aplikace
            $cache = new Cache($storage);
            $cache->clean([Cache::ALL => true]); // Vymazání veškeré cache

            $this->redirect(':CommonModule:Home:default');

        }

        // TODO: Store The Time of Activity
        $session->lastActivity = time();
        Debugger::log("User activity updated at " . date('Y-m-d H:i:s', $session->lastActivity), Debugger::INFO);
    }
}