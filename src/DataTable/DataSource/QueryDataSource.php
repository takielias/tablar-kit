<?php

declare(strict_types=1);

namespace Takielias\TablarKit\DataTable\DataSource;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

class QueryDataSource implements TableDataContract
{
    public Builder $query;

    /**
     * @param Builder $query
     */
    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    public function search(?string $search, string $type, array $columns): void
    {
        $this->query->where(function (Builder $query) use ($search, $type, $columns) {
            foreach ($columns as $column) {
                if ($column['search']) {
                    $columnName = $column['id'] ?? $column['field'];
                    if ($type == 'like') {
                        $query->orWhere($columnName, 'like', '%' . $search . '%');
                    } else {
                        $query->orWhere($columnName, $type, $search);
                    }
                }
            }
        });
    }

    public function sort(?array $orders, ?array $dirs): void
    {
        collect($orders)->each(fn($field, $index) => $this->query->orderBy($field, $dirs[$index] ?? 'asc'));
    }

    public function paginate($perPage = 10, $columns = ['*'], $pageName = 'page', $page = null): Paginator
    {
        return $this->query->paginate($perPage);
    }
}
