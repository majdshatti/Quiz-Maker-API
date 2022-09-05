<?php

/**
 * Returns a message suitable for response, can be an error or success message.
 *
 * @param string $keyWord Key word that represents sentance in the switch statment.
 *
 * @return String message
 */
if (!function_exists("getResMessage")) {
    function getResMessage($keyWord, $options = [])
    {
        $path = $options["path"] ?? ""; // Example: User, Quiz, Subject
        $value = $options["value"] ?? ""; // Example: Majd, 23124, majd-nuzha
        $field = $options["field"] ?? ""; // Example: name, createdAt, slug

        switch ($keyWord) {
            case "alreadyVerfied":
                return "Account is already verified!";
            case "authenticate":
                return "Unauthenticated to access this route";
            case "authorize":
                return "Unauthorized to access this route";
            case "badRequest":
                return "Bad Request";
            case "created":
                return ($path ? ucfirst($path) : "Record") .
                    " is created successfully";
            case "creds":
                return "invalid creds";
            case "deleted":
                return ($path ? ucfirst($path) : "Record") .
                    " is deleted successfully";
            case "edited":
                return ($path ? ucfirst($path) : "Record") .
                    " is edited successfully";
            case "forbbiden":
                return "Forbbiden";
            case "invalidToken":
                return "Invailed token provided";
            case "logout":
                return "Logged out";
            case "notCorrect":
                return ($value ? ucfirst($value) : "Value") . " does not correct";
            case "notExist":
                return ($value ? ucfirst($value) : "Value") . " does not exist";
            case "notFound":
                return "Not found";
            case "notUnique":
                return ucfirst($path) .
                    " with the same {$field} already exists";
            case "registered":
                return "Account registered successfully";
            case "serverError":
                return "Something went wrong while trying to " .
                    ($path ? strtolower($path) : "perform this operation") .
                    ", if problem persists contact us at info@company.com";
            case "verified":
                return "Account verified successfully";
            
            default:
                return false;
        }
    }
}

?>
