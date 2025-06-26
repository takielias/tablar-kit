<?php


namespace TakiElias\TablarKit\Enums;

enum ExportType: string
{
    case CSV = 'csv';
    case XLS = 'xls';
    case PDF = 'pdf';
    case HTML = 'html';
}
