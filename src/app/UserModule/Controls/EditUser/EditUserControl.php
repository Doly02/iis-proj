<?php

namespace App\UserModule\Controls\EditUser;

use Nette\Application\UI\Control;
use App\UserModule\Model\UserService;
use App\UserModule\Model\UserFormFactory;
use \Nette\Database\Table\ActiveRow;
use Nette\Application\UI\Form;
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

    public function createComponentEditUser(): Form
    {
        $form = new Form();

        /* First Name */
        $form->addText('name', 'First Name')
            ->setDefaultValue($this->_user->name)
            ->setRequired('Please Fill Your First Name.')
            ->setHtmlAttribute('class', 'form-control');

        /* Last Name */
        $form->addText('lastName', 'Last Name')
            ->setDefaultValue($this->_user->surname)
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

    public function editUserFormSucceeded(Form $form, \stdClass $values) : void
    {
        try {
            $data = [
                'name' => $values->name,
                'lastName' => $values->lastName,
                'email' => $values->email,
            ];

            // If Password Was Filled
            if (!empty($values->password)) {
                $passwords = new \Nette\Security\Passwords();
                $data['password'] = $passwords->hash($values->password);
            }

            // Update User In Database
            $this->_userService->editUser($this->_user, $data);

            $this->presenter->flashMessage('User information updated successfully.', 'success');
            $this->presenter->redirect('this');
        } catch (\Exception $e) {
            $form->addError('An error occurred while updating user information: ' . $e->getMessage());
        }
    }

    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/../../templates/User/editUser.latte');
        $this->template->render();
    }
}