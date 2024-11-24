<?php

namespace App\UserModule\Controls\DetailUser;

use Nette\Application\UI\Control;
use App\UserModule\Model\UserService;
use App\UserModule\Model\UserFormFactory;
use \Nette\Database\Table\ActiveRow;
use Nette\Application\UI\Form;

final class DetailUserControl extends Control
{
    /*
     * @brief User That Will Be Edited.
     */
    private $_user;

    private $_userFormFactory;

    private $_userService;

    public function __construct(
        UserFormFactory $userFormFactory,
        UserService     $userService,
        ActiveRow       $user
    )
    {
        $this->_user = $user;
        $this->_userFormFactory = $userFormFactory;
        $this->_userService = $userService;
    }
}