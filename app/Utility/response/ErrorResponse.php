<?php

/**
 * Returns error response to the client.
 *
 * @param Throwable $exception An instance of throwable exception class.
 *
 * @return JsonResponse
 */
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
        $response["message"] = $exception->getMessage();

        if (method_exists($exception, "errors")) {
            $response["errors"] = $exception->errors();
        }

        // Return json response
        return response()->json($response, $statusCode);
    }
}

?>
