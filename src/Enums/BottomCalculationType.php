<?php


namespace TakiElias\TablarKit\Enums;

enum BottomCalculationType: string
{
    case AVG = 'avg';
    case MAX = 'max';
    case MIN = 'min';
    case SUM = 'sum';

    case PRECISION  = 'precision ';

    case CONCAT  = 'concat ';

    case COUNT  = 'count ';

    case UNIQUE  = 'unique ';
}
