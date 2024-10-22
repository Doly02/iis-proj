<?php

declare(strict_types=1);

namespace App\CommonModule\Presenters;

use Nette\Application\UI\Presenter;

abstract class SecurePresenter extends BasePresenter
{
    public function startup() : void
    {
        parent::startup();
        $this->checkLoggedIn();
    }

    protected function checkLoggedIn() : void
    {
        $user = $this->getUser();
        $session = $this->getSession('user_activity');

        /* If User Is Not Logged In */
        if (!$user->isLoggedIn())
        {
            $this->flashMessage("You have been logged out due to inactivity.", 'info');
            $this->redirect(':UserModule:Authentication:signIn', [
                'login-backLink' => $this->storeRequest()
            ]);
        }

        /* Check of Unactivity -> If User Has No Activity Durring 30 Minutes Then He/She Will Be Logged Out
         * 30 min. == 1800 sec.
         */
        if (isset($session->lastActivity) && (time() - $session->lastActivity > 1800))
        {
            $this->getUser()->logout();
            $this->flashMessage("You have been logged out due to inactivity.", 'info');
            $this->redirect(':User:Authentication:SignIn');
        }

        // TODO: Store The Time of Activity
        $session->lastActivity = time();
    }
}