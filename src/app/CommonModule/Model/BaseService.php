<?php

namespace App\CommonModule\Model;

use Nette\Database\Table\Selection;
use Nette\Database\Explorer;

abstract class BaseService
{
    use \Nette\SmartObject;

    protected Explorer $database;

    public function __construct(Explorer $database)
    {
        $this->database = $database;
    }

    abstract public function getTableName(): string;

    public function getTable() : Selection
    {
        return $this->database->table($this->getTableName());
    }

    public function selectionCallback(?callable $callback = null) : Selection
    {
        $selection = $this->getTable();
        if (null !== $callback)
        {
            $callback($selection);
        }
        return $selection;
    }

    public function fetch(callable $callback = null) : ?\Nette\Database\Table\ActiveRow
    {
        return $this->selectionCallback($callback)->fetch() ?: null;
    }

    public function fetchById(int $id) : ?\Nette\Database\Table\ActiveRow
    {
        return $this->getTable()->get($id) ?: null;
    }

    /**
     * @return array|\Nette\Database\Table\ActiveRow[]
     */
    public function fetchAll(callable $callback = null): array
    {
        return $this->selectionCallback($callback)->fetchAll();
    }

    public function fetchPairs(string $key = null, string $value = null, callable $callback = null): array
    {
        return $this->selectionCallback($callback)->fetchPairs($key, $value);
    }
}