<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNan;

/**
 * Get minutes from `20m` format
 *
 * @param  $tableName
 * @param  $path
 *
 * @return int
 */

if (!function_exists("getMinutesCustomFormat")) {
    function getMinutesCustomFormat($minutes)
    {
        $minutes = (int) substr_replace($minutes, "", -1);

        if (is_nan($minutes)) {
            return -1;
        }

        return $minutes;
    }
}
