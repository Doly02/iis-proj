<?php

declare(strict_types=1);

namespace App\ConferenceModule\Controls\AddConference;

use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Application\UI\Control;

final class AddConferenceControl extends Control
{
    /** @var User */
    private $user;

    public function __construct(\Nette\Security\User $user)
    {
        $this->user = $user;
    }

    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/templates/ConferenceAdd.latte');
        $this->template->render();
    }

}
