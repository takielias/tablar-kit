<?php

namespace TakiElias\TablarKit\DataTable\DataSource;

class ArrayDataSource extends CollectionDataSource implements TableDataContract
{
    public function __construct(array $data)
    {
        parent::__construct(collect($data));
    }
}
