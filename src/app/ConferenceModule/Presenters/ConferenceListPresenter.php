<?php

namespace App\ConferenceModule\Presenters;

use App\CommonModule\Presenters\BasePresenter;

final class ConferenceListPresenter extends BasePresenter
{
    public function renderList(): void
    {
        $this->template->title = 'List of Conferences';

        $this->template->conferences = $this->database
            ->table('conferences')
            ->order('start_date ASC');
    }
}