<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Components\Table;

use TakiElias\TablarKit\Components\TablarComponent;
use TakiElias\TablarKit\DataTable\DataTable;

class Tabulator extends TablarComponent
{
    public ?DataTable $table;
    public ?string $id;

    public function __construct(DataTable $table, string $id = null)
    {
        $this->table = $table;
        $this->id = $id ?? 'id_' . uniqid();
    }

    public function render()
    {
        $request = request();

        return view('tablar-kit::components.table.tabulator', [
            'baseUrl' => $request->url(),
            'columns' => $this->table->columns,
            'export_types' => $this->table->exportTypes,
            'rows' => $this->table->getData($request)
        ]);

    }
}
