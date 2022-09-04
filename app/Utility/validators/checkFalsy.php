<?php

use Mockery\Undefined;

if (!function_exists("isFalsy")) {
    function isFalsy($value)
    {
        $isFalsy = false;

        if (is_null($value) === true) {
            $isFalsy = true;
        }
        if ($value === null) {
            $isFalsy = true;
        }
        if ($value === false) {
            $isFalsy = true;
        }
        if ($value === 0) {
            $isFalsy = true;
        }
        if (isset($value) === false) {
            $isFalsy = true;
        }
        if (empty($value) === true) {
            $isFalsy = true;
        }
        if (is_array($value) && count($value) < 1) {
            $isFalsy = true;
        }

        return $isFalsy;
    }
}

?>
