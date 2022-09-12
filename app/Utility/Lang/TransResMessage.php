<?php

use Illuminate\Support\Facades\Lang;

/**
 *
 * @param string       $keyword
 * @param assoc_array  $option
 *
 * @return string
 */
if (!function_exists("transResMessage")) {
    function transResMessage($keyword, $option = [])
    {
        $path = $option["path"] ?? "path";
        $field = $option["field"] ?? "field";
        $value = $option["value"] ?? "value";

        return ucfirst(
            handleSentanceStart(
                Lang::get("resMessages." . $keyword, [
                    "path" => transKeyword($path),
                    "value" => transKeyword($value),
                    "field" => transKeyword($field),
                ])
            )
        );
    }
}

/**
 *
 *
 * @param string $sentance
 *
 * @return string
 */
function handleSentanceStart(string $sentance)
{
    if (app()->getLocale() === "en") {
        return ucfirst($sentance);
    }
    if (app()->getLocale() === "ar") {
        return "ال" . $sentance;
    }
}

?>
