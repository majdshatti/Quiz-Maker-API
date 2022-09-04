<?php

if (!function_exists("getResMessage")) {
    function getResMessage(string $keyWord)
    {
        switch ($keyWord) {
            case "registered":
                return "Account registered successfully";
            default:
                return false;
        }
    }
}

?>
