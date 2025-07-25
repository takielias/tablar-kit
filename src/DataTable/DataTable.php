<?php

namespace TakiElias\TablarKit\DataTable;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use TakiElias\TablarKit\DataTable\DataSource\ArrayDataSource;
use TakiElias\TablarKit\DataTable\DataSource\CollectionDataSource;
use TakiElias\TablarKit\DataTable\DataSource\QueryDataSource;
use TakiElias\TablarKit\DataTable\DataSource\TableDataContract;
use TakiElias\TablarKit\Enums\BottomCalculationType;
use TakiElias\TablarKit\Enums\ExportType;

class DataTable
{
    protected Builder $query;

    protected TableDataContract $dataSource;

    public array $columns;

    public array $exportTypes;

    protected array $callbacks;

    protected int $limit = 10;

    // Add an array to track hidden columns
    protected array $hiddenColumns = [];

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

    // Add method to hide columns from auto-generation
    public function hideColumns(array $columns): self
    {
        $this->hiddenColumns = array_merge($this->hiddenColumns, $columns);
        return $this;
    }

    public function column(string                $name,
                           string                $title,
                                                 $callback = null,
                           string                $formatter = 'plaintext',
                           array                 $formatterParams = [],
                           bool                  $search = false,
                           ?string               $width = '200',
                           bool                  $download = true,
                           BottomCalculationType $bottomCalc = null,
                           bool                  $hidden = false // Add hidden parameter
    ): self
    {
        // If a column is marked as hidden, add to a hidden columns array
        if ($hidden) {
            $this->hiddenColumns[] = $name;
            return $this;
        }

        $column = [
            'field' => $name,
            'title' => $title,
            'width' => $width,
            'search' => $search,
            'formatter' => $formatter,
            'headerMenu' => 'headerMenu',
            'download' => $download,
            'bottomCalc' => $bottomCalc?->value,
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
            'hidden_columns' => $this->hiddenColumns, // Pass hidden columns to view
        ]);
    }

    protected function format($data): Collection
    {
        return collect($data)->map(function ($item) {
            $formatted = is_array($item) ? $item : $item->toArray();

            // Remove hidden columns from formatted data
            foreach ($this->hiddenColumns as $hiddenColumn) {
                unset($formatted[$hiddenColumn]);
            }

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

    protected function paginate($limit): void
    {
        $this->limit = $limit;
    }

    protected function getPaginate($limit): Paginator
    {
        return $this->dataSource->paginate($limit);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getData(Request $request)
    {
        $limit = $request->limit ?? $this->limit;
        $paginator = $this
            ->search($request)
            ->sort($request)
            ->getPaginate($limit);

        return [
            'data' => $this->format($paginator->items()),
            'total_rows' => round($paginator->total() / $limit),
            'currentPage' => $paginator->currentPage(),
            'search' => $request->search,
            'download' => $request->download,
            'bottomCalc' => $request->bottomCalc,
            'order' => $request->order,
            'dir' => $request->dir,
            'limit' => $paginator->perPage()
        ];
    }
}
