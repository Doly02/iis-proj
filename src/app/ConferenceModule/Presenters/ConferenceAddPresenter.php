<?php

namespace App\ConferenceModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;
use Nette\Application\UI\Form;
use Nette\Application\AbortException;

final class ConferenceAddPresenter extends BasePresenter
{

    // Creates Add Conference Form
    protected function createComponentAddConferenceForm(): Form
    {
        $form = new Form;

        $form->addText('name', 'Name:')
            ->setRequired('Enter the conference name.')
            ->addRule($form::MaxLength, 'Name can be at most 30 characters long.', 30);

        $form->addTextArea('description', 'Description:')
            ->setRequired('Enter a description for the conference.');

        // User-friendly selection of date and time
        $form->addDate('start_date', 'Start Date:')
            ->setRequired('Enter the start date.');

        $form->addTime('start_time', 'Start Time:')
            ->setRequired('Enter the start time.');

        $form->addDate('end_date', 'End Date:')
            ->setRequired('Enter the end date.');

        $form->addTime('end_time', 'End Time:')
            ->setRequired('Enter the end time.');

        $form->addText('price', 'Price:')
            ->setRequired('Enter the price.')
            ->addRule($form::Float, 'Price must be a number.');

        $form->addInteger('capacity', 'Capacity:')
            ->setRequired('Enter the capacity.')
            ->addRule($form::Min, 'Capacity must be at least 1.', 1);

        $form->addSubmit('send', 'Add Conference');

        $form->onSuccess[] = [$this, 'addConferenceFormSucceeded'];
        return $form;
    }

    public function addConferenceFormSucceeded(Form $form, \stdClass $values): void
    {
        try {
            // Insert the conference data into the database
            $this->database->table('conferences')->insert([
                'name' => $values->name,
                'description' => $values->description,
                'start_time' => $values->start_time,
                'end_time' => $values->end_time,
                'start_date' => $values->start_date,
                'end_date' => $values->end_date,
                'price' => $values->price,
                'capacity' => $values->capacity, // TODO: Add together capacity of rooms
                'organiser_id' => 1, // TODO: User id
            ]);

            $this->flashMessage('Conference added successfully.', 'success');
            // Wrap the redirection in a try-catch to catch any exceptions

            $this->redirect(destination: ':CommonModule:Home:default');


        } catch (AbortException $e) {
            // Allow AbortException silently (no action needed)
        } catch (\Exception $e) {
            $form->addError('An error occurred while adding the conference: ' . $e->getMessage());
        }
    }
}