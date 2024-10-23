<?php

namespace App\UserModule\Model;

enum Action: string
{
    case ADD = 'add';
    case EDIT = 'edit';
    case LIST = 'list';
    case DELETE = 'delete';
}
