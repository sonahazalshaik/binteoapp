<?php

namespace App\Exports\Admin;

use Illuminate\Support\Collection;

abstract class AdminBaseExport
{
    protected array $columns = [];
    protected array $headers = [];
    protected string $module;

    abstract protected function query();

    public function __construct(string $module)
    {
        $this->module = $module;
    }

    public function getData(): Collection
    {
        return $this->query()->get();
    }

    public function headers(): array
    {
        return $this->headers;
    }

    public function columns(): array
    {
        return $this->columns;
    }

    public function map($row): array
    {
        $data = [];
        foreach ($this->columns as $col) {
            $data[] = data_get($row, $col, '');
        }
        return $data;
    }
}
