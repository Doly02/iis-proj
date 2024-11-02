<?php

namespace App\UserModule\Controls\EditUser;

use Nette\Application\UI\Control;
use App\UserModule\Model\UserService;
use App\UserModule\Model\UserFormFactory;
use \Nette\Database\Table\ActiveRow;
final class EditUserControl extends Control
{
    /*
     * @brief User That Will Be Edited.
     */
    private $_user;

    private $_userFormFactory;

    private $_userService;

    public function __construct(
        UserFormFactory $userFormFactory,
        UserService $userService,
        ActiveRow $user
    )
    {
        $this->_user = $user;
        $this->_userFormFactory = $userFormFactory;
        $this->_userService = $userService;
    }

    public function createComponentEditUser(): \Nette\Application\UI\Form
    {
        $form = new \Nette\Application\UI\Form();

        /* First Name */
        $form->addText('name', 'First Name')
            ->setDefaultValue($this->_user->name)
            ->setRequired('Please Fill Your First Name.')
            ->setHtmlAttribute('class', 'form-control');

        /* Last Name */
        $form->addText('lastName', 'Last Name')
            ->setDefaultValue($this->_user->lastName)
            ->setRequired('Please Fill Your Last Name.')
            ->setHtmlAttribute('class', 'form-control');

        /* Email Address */
        $form->addText('email', 'Email Address')
            ->setDefaultValue($this->_user->email)
            ->setRequired('Please Fill Your Email Address.')
            ->addRule(function ($control) {
                return \Nette\Utils\Validators::isEmail($control->value);
            }, 'Please Enter a Valid Email Address.')
            ->setHtmlAttribute('class', 'form-control');

        /* Password */
        $form->addPassword('password', 'Password')
            ->setHtmlAttribute('class', 'form-control');

        /* Password Confirmation */
        $form->addPassword('passwordConfirmation', 'Confirm Password')
            ->addRule(function ($control) use ($form) {
                return $control->value === $form['password']->getValue();
            }, 'Passwords do not match.')
            ->setHtmlAttribute('class', 'form-control');

        /* Submission Button */
        $form->addSubmit('submit', 'Save Changes')
            ->setHtmlAttribute('class', 'btn btn-primary');

        $form->onSuccess[] = [$this, 'editUserFormSucceeded'];
        return $form;
    }

    public function editUserFormSucceeded(\Nette\Application\UI\Form $form, \stdClass $values) : void
    {
        try {
            $data = [
                'name' => $values->name,
                'lastName' => $values->lastName,
                'email' => $values->email,
            ];

            // Pokud bylo vyplněno heslo, přidáme ho do dat k uložení
            if (!empty($values->password)) {
                $passwords = new \Nette\Security\Passwords();
                $data['password'] = $passwords->hash($values->password);
            }

            // Aktualizace uživatele v databázi
            $this->_userService->editUser($this->_user, $data);

            $this->presenter->flashMessage('User information updated successfully.', 'success');
            $this->presenter->redirect('this');
        } catch (\Exception $e) {
            $form->addError('An error occurred while updating user information: ' . $e->getMessage());
        }
    }
}