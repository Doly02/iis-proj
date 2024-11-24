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

    private bool $adminEdit;


    public function __construct(
        UserFormFactory $userFormFactory,
        UserService $userService,
        ActiveRow $user,
        Bool $adminEdit
    )
    {
        $this->_user = $user;
        $this->_userFormFactory = $userFormFactory;
        $this->_userService = $userService;
        $this->adminEdit = $adminEdit;
    }

    public function setList()
    {
        $this->_list = true;
    }

    public function createComponentEditUser(): Form
    {
        $form = new Form();

        $form->addHidden('userId', $this->_user->id);

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
                'surname' => $values->lastName,
                'email' => $values->email,
            ];

            // If Password Was Filled
            if (!empty($values->password)) {
                $passwords = new \Nette\Security\Passwords();
                $data['password'] = $passwords->hash($values->password);
            }

            $userId = $values->userId;
            // Update User In Database
            $this->_userService->editUserById($userId, $data);

            $this->presenter->flashMessage('User information updated successfully.', 'success');

        }
        catch (\Exception $e)
        {
            $form->addError('An error occurred while updating user information: ' . $e->getMessage());
        }
        if(!$this->adminEdit)
            $this->presenter->redirect(':UserModule:UserDetail:default');
        else
            $this->presenter->redirect(':UserModule:UserList:list');
    }

    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/../../templates/User/editUser.latte');
        $this->template->render();
    }
}