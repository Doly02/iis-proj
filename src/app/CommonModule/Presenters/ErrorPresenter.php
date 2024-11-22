<?php declare(strict_types = 1);

namespace App\CommonModule\Presenters;

use Nette;
use Nette\Application\BadRequestException;
use Nette\Application\InvalidPresenterException;
use Nette\Application\Request;
use Nette\Application\UI\Presenter;

final class ErrorPresenter extends Presenter
{
    public function renderDefault(Request $request): void
    {
        $exception = $request->getParameter('exception');

        if ($exception instanceof InvalidPresenterException) {
            // Pokud presenter neexistuje, zobrazíme chybovou stránku 404
            $this->setView('404');
        } elseif ($exception instanceof BadRequestException) {
            // Chyba 4xx - například 404 Stránka nenalezena
            $code = $exception->getCode();
            $this->setView(in_array($code, [403, 404, 405, 410]) ? (string)$code : '4xx');
        } else {
            // Chyba 500 - Interní chyba serveru
            $this->setView('500');
        }

        $this->template->exception = $exception;
    }
}