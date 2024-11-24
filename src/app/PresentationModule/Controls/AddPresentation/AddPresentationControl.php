<?php

declare(strict_types=1);

namespace App\PresentationModule\Controls\AddPresentation;

use Nette\Application\UI\Control;

final class AddPresentationControl extends Control
{
    /** @var User */
    private $user;

    public function __construct(\Nette\Security\User $user)
    {
        $this->user = $user;
    }

    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/templates/PresentationAdd.latte');
        $this->template->render();
    }

}
