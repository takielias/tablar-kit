<?php

namespace Takielias\TablarKit\DataTable\DataSource;


class ArrayDataSource extends CollectionDataSource implements TableDataContract
{
    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct(collect($data));
    }
}
