<?php

declare(strict_types=1);

namespace App\CommonModule\Presenters;

use App\CommonModule\Controls\Footer\FooterControl;
use App\CommonModule\Controls\Footer\IFooterControlFactory;
use App\CommonModule\Controls\HeadBar\HeadBarControl;
use App\CommonModule\Controls\HeadBar\IHeadBarControlFactory;
use App\CommonModule\Controls\SideBar\ISideBarControlFactory;
use App\CommonModule\Controls\SideBar\SideBarControl;
use Nette\Application\UI\Presenter;
use Others\SSL\SslOperations;

abstract class BasePresenter extends Presenter
{
    private IFooterControlFactory $footerControlFactory;
    private IHeadBarControlFactory $headBarControlFactory;
    private ISideBarControlFactory $sideBarControlFactory;


    protected string $accountType = 'unknown';
    public function startup(): void
    {
        parent::startup();
        /* Launch Session */
        $this->getSession()->start();

        $accountTypeCookie = $this->getHttpRequest()->getCookie('mode');
        $ssl = new SslOperations();
        if ($accountTypeCookie) {
            $accountType = $ssl->decryptAccountType($accountTypeCookie);

            if ($accountType === 'user' || $accountType === 'admn') {
                $this->accountType = $accountType;
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


    public function handleLogout(): void
    {
        $this->getUser()->logout(true);
        $this->flashMessage('You Have Been Logged Out.', 'info');
        $this->redirect(':ConferenceModule:ConferenceList:list');
    }


    public function injectFooterControlFactory(IFooterControlFactory $footerControlFactory): void
    {
        $this->footerControlFactory = $footerControlFactory;
    }

    public function injectHeadBarControlFactory(IHeadBarControlFactory $headBarControlFactory): void
    {
        $this->headBarControlFactory = $headBarControlFactory;
    }

    public function injectSideBarControlFactory(ISideBarControlFactory $sideBarControlFactory): void
    {
        $this->sideBarControlFactory = $sideBarControlFactory;
    }

    protected function createComponentFooter(): FooterControl
    {
        return $this->footerControlFactory->create();
    }

    protected function createComponentHeadBar(): HeadBarControl
    {
        return $this->headBarControlFactory->create();
    }

    protected function createComponentSideBar(): SideBarControl
    {
        return $this->sideBarControlFactory->create($this->accountType);
    }
}