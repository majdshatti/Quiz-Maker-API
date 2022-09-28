<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isNan;

if (!function_exists("substrDates")) {
    function substrDates($date1, $date2)
    {
        $date1 = new DateTime($date1);
        $date2 = new DateTime($date2);

        $result = $date1->getTimestamp() - $date2->getTimestamp();

        return $result;
    }
}

?>
