<?php

if (!function_exists("getResMessage")) {
    function getResMessage(string $keyWord)
    {
        switch ($keyWord) {
            case "401":
                return "Unauthorized to access this route";
            case "403":
                return "Forbbiden";
            case "404":
                return "Not found";
            case "422":
                return "Bad Request";
            case "500":
                return "Something went wrong :<, try again later";
            case "registered":
                return "Account registered successfully";
            default:
                return false;
        }
    }
}

?>
