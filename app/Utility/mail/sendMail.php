<?php

use Illuminate\Support\Facades\Mail;

/**
 * Sends email using smtp
 *
 * @param string $mail    Destination mail that message is sent to.
 * @param string $subject The subject of the sent message.
 * @param string $body    The body of the sent message.
 *
 * @return boolean
 */
if (!function_exists("sendMail")) {
    function sendMail($email, $subject, $body)
    {
        $data = [
            "email" => $email,
            "subject" => $subject,
            "body" => $body,
        ];

        try {
            Mail::send([], $data, function ($message) use ($data) {
                $message->to($data["email"]);
                $message->subject($data["subject"]);
                $message->text($data["body"]);
            });

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }
}

?>
