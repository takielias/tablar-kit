<?php

namespace Takielias\TablarKit\DataTable\DataSource;

use Illuminate\Contracts\Pagination\Paginator;

interface TableDataContract
{
    public function search(?string $search, string $type, array $columns): void;

    public function sort(?array $orders, ?array $dirs): void;

    public function paginate($perPage, $columns, $pageName, $page): Paginator;
}
