<?php

use Illuminate\Support\Facades\Lang;

/**
 *
 *
 * @param
 *
 * @return
 */
if (!function_exists("transKeyword")) {
    function transKeyword($keyword)
    {
        return Lang::get("keyWords." . strtolower($keyword)) ?? "null";
    }
}

?>
