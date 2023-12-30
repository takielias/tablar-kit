<?php

namespace Takielias\TablarKit\DataTable;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Takielias\TablarKit\DataTable\DataSource\ArrayDataSource;
use Takielias\TablarKit\DataTable\DataSource\CollectionDataSource;
use Takielias\TablarKit\DataTable\DataSource\QueryDataSource;
use Takielias\TablarKit\DataTable\DataSource\TableDataContract;
use Takielias\TablarKit\Enums\ExportType;

class DataTable
{
    protected Builder $query;
    protected TableDataContract $dataSource;

    public array $columns;

    public array $exportTypes;

    protected array $callbacks;

    public function __construct()
    {
        $this->columns = [];
        $this->callbacks = [];
        $this->exportTypes = [];
    }

    public function setDataSource(Builder|Collection|array $source): static
    {
        if ($source instanceof Builder) {
            $this->fromQuery($source);
        } else if ($source instanceof Collection) {
            $this->fromCollection($source);
        } else {
            $this->fromArray($source);
        }
        return $this;
    }


    // Function to set multiple export types
    public function setExportTypes(array $types): static
    {
        $this->exportTypes = array_map(fn(ExportType $type) => $type->value, $types);
        return $this;
    }

    public function fromQuery(Builder $query): self
    {
        $this->dataSource = app(QueryDataSource::class, ['query' => $query]);

        return $this;
    }

    public function fromCollection(Collection $data): self
    {
        $this->dataSource = app(CollectionDataSource::class, ['data' => $data]);

        return $this;
    }

    public function fromArray(array $data): self
    {
        $this->dataSource = app(ArrayDataSource::class, ['data' => $data]);

        return $this;
    }

    public function column(string $name, string $title, $callback = null, string $formatter = 'plaintext', array $formatterParams = [], bool $search = false, ?string $width = '200'): self
    {
        $column = [
            'field' => $name,
            'title' => $title,
            'width' => $width,
            'search' => $search,
            'formatter' => $formatter,
            'headerMenu' => 'headerMenu',
        ];

        if (!empty($formatterParams)) {
            $column['formatterParams'] = $formatterParams;
        }

        $this->columns[] = $column;

        $this->callbacks[$name] = $callback;

        return $this;
    }

    public function render(string $view = 'tablar-kit::table.datatable')
    {
        $request = \request();
        if ($request->expectsJson()) {
            return $this->getData($request);
        }
        return view($view, [
            'baseUrl' => $request->url(),
            'rows' => $this->getData($request),
            'columns' => $this->columns,
            'export_types' => $this->exportTypes,
        ]);
    }

    protected function format($data): Collection
    {
        return collect($data)->map(function ($item) {
            $formatted = is_array($item) ? $item : $item->toArray();
            foreach ($this->callbacks as $field => $callback) {
                if (is_callable($callback)) {
                    $formatted[$field] = $callback($item);
                }
            }
            return $formatted;
        });
    }

    protected function search(Request $request): self
    {
        $filters = $request->input('filter', []);

        foreach ($filters as $filter) {
            if (isset($filter['type'], $filter['value'])) {
                $searchString = $filter['value'];
                $type = $filter['type'];
                // Assuming 'search' is a flag indicating whether the column is searchable
                // You might need to adjust this part based on your actual column data structure
                if (!empty($filter['field'])) {
                    $columns = [['id' => $filter['field'], 'search' => true]];
                } else {
                    $columns = $this->columns;
                }

                $this->dataSource->search($searchString, $type, $columns);
            }
        }

        return $this;
    }

    protected function sort(Request $request): self
    {
        $sorts = $request->input('sort', []);
        $orders = [];
        $dirs = [];

        foreach ($sorts as $sort) {
            if (isset($sort['field'], $sort['dir'])) {
                $orders[] = $sort['field'];
                $dirs[] = $sort['dir'];
            }
        }

        if (!empty($orders) && !empty($dirs)) {
            $this->dataSource->sort($orders, $dirs);
        }

        return $this;
    }

    protected function paginate($limit): Paginator
    {
        return $this->dataSource->paginate($limit);
    }

    /**
     * @param $request
     * @return array
     */
    public function getData(Request $request)
    {

        $limit = $request->limit ?? 10;
        $paginator = $this
            ->search($request)
            ->sort($request)
            ->paginate($limit);

        return [
            'data' => $this->format($paginator->items()),
            'total_rows' => round($paginator->total() / $limit),
            'currentPage' => $paginator->currentPage(),
            'search' => $request->search,
            'order' => $request->order,
            'dir' => $request->dir,
            'limit' => $paginator->perPage()
        ];
    }
}

