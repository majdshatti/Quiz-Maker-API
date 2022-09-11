<?php

/**
 * Returns success response with payload of message and/or data and/or token.
 *
 * @param Array $option can contain the keys of message, data and token.
 * @param int   $statusCode status code to be gived to the response.
 *
 * @return JsonResponse
 */
if (!function_exists("sendSuccRes")) {
    function sendSuccRes($option, $statusCode = 200)
    {
        $response = null;

        // Set message
        if (isset($option["keyMessage"])) {
            $response["message"] = getResMessage($option["keyMessage"]) ?? "";
        } elseif (isset($option->message)) {
            $response["message"] = $option["keyMessage"];
        }

        // Set data
        if (isset($option["data"])) {
            $response["data"] = $option["data"];
        }

        // Set token
        if (isset($option["token"])) {
            $response["token"] = $option["token"];
        }

        // Return success response
        return response($response, $statusCode);
    }
}

?>
