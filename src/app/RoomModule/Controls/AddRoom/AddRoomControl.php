<?php

declare(strict_types=1);

namespace App\RoomModule\Controls\AddRoom;

use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Application\UI\Control;

final class AddRoomControl extends Control
{
    /** @var User */
    private $user;

    public function __construct(\Nette\Security\User $user)
    {
        $this->user = $user;
    }

    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/templates/RoomAdd.latte');
        $this->template->render();
    }

}
