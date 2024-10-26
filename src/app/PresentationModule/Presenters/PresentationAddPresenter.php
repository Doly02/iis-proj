<?php

namespace App\PresentationModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use Nette\Application\UI\Form;
use Nette\Application\AbortException;

final class PresentationAddPresenter extends BasePresenter
{

    protected function createComponentAddPresentationForm(): Form
    {
        $form = new Form;

        $form->addText('name', 'Name:')
            ->setRequired('Enter the presentation name.')
            ->addRule($form::MaxLength, 'Name can be at most 30 characters long.', 30);

        $form->addTextArea('description', 'Description:');

        $form->addTextArea('attachment', 'Attachment:');

        $form->addSubmit('send', 'Add');

        $form->onSuccess[] = [$this, 'addPresentationFormSucceeded'];
        return $form;
    }

    public function addPresentationFormSucceeded(Form $form, \stdClass $values): void
    {
        try {
            // Insert the room data into the database
            $this->database->table('presentations')->insert([
                'name' => $values->name,
                'description' => $values->description,
                'attachment' => $values->attachment,
                'conference_id' => 1, // TODO: passed from conference detail
                'room_id' => 1, // TODO: Drop FK, set to null as default
                'lecturer_id' => 1, // TODO: User id
            ]);

            $this->flashMessage('Presentation added successfully.', 'success');

            $this->redirect(destination: ':CommonModule:Home:default'); // not working


        } catch (AbortException $e) {
            // Allow AbortException silently (no action needed)
        } catch (\Exception $e) {
            $form->addError('An error occurred while adding the room: ' . $e->getMessage());
        }
    }
}