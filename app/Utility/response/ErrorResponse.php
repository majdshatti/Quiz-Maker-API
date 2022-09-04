<?php

if (!function_exists("errorResponse")) {
    function errorResponse($exception)
    {
        // Check for a status code in the exception
        if (isset($exception->status)) {
            $statusCode = $exception->status;
        } else {
            $statusCode = 500;
        }

        // Response object
        $response = [];
        $response["success"] = false;

        // Check exception status and set response message
        switch ($statusCode) {
            case 401:
                $response["message"] = getResMessage("401");
                break;
            case 403:
                $response["message"] = getResMessage("403");
                break;
            case 404:
                $response["message"] = getResMessage("404");
                break;
            case 405:
                $response["message"] = getResMessage("405");
                break;
            case 422:
                $response["message"] = getResMessage("422");
                $response["errors"] = $exception->errors();
                break;
            default:
                $response["message"] =
                    $statusCode == 500
                        ? getResMessage("500")
                        : $exception->getMessage();
                break;
        }

        // Return json response
        return response()->json($response, $statusCode);
    }
}

?>
