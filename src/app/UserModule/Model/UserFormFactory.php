<?php

declare(strict_types=1);

namespace App\UserModule\Model;

use Nette\Application\UI\Form;
use \Nette\SmartObject;
use Nette\Utils\Validators;
use Tracy\OutputDebugger;


final class UserFormFactory
{
    public function createAdminForm() : Form
    {
        /*
         * TODO Maybe Add Some Extra Check For The Password Like:
         * "Password Should Have At Least One Special Character..."
        */
        $form = new Form();

//        OutputDebugger::enable();

        /* First Name */
        $form->addText('name', 'First Name')
            ->setRequired('Please Fill Your First Name.')
            ->setHtmlAttribute('class', 'form-control');

        /* Last Name */
        $form->addText('lastName', 'Last Name')
            ->setRequired('Please Fill Your Last Name.')
            ->setHtmlAttribute('class', 'form-control');

        /* Email Address */
        $form->addText('email', 'Email Address')
            ->setRequired('Please Fill Your Email Address.')
            ->addRule(function ($control) {
                return Validators::isEmail($control->value); /* Check of The Address Structure */
            }, 'Please Enter a Valid Email Address.')
            ->setHtmlAttribute('class', 'form-control');

        /* Password */
        $form->addPassword('password', 'Password')
            ->setRequired('Please Fill Your Password.')
            ->setHtmlAttribute('class', 'form-control');

        /* Confirm Password */
        $form->addPassword('passwordConfirmation', 'Confirm Password')
            ->setRequired('Please Confirm Your Password.')
            ->addRule(function ($control) use ($form)
            {
                return $control->value === $form['password']->getValue();
            }, 'Passwords do not match.')
            ->setHtmlAttribute('class', 'form-control');

        /* Button For Submission of The Form */
        $form->addSubmit('submit', 'Store')
            ->setHtmlAttribute('class', 'btn btn-primary');

        return $form;
    }

    public function createUserForm() : Form
    {
        return $this->createAdminForm();
    }

}