<?php

use Illuminate\Support\Facades\Mail;

if (!function_exists("sendMail")) {
    function sendMail($email, $subject, $body)
    {
        $data = [
            "email" => $email,
            "subject" => $subject,
            "body" => $body,
        ];

        Mail::send([], $data, function ($message) use ($data) {
            $message->to($data["email"]);
            $message->subject($data["subject"]);
            $message->text($data["body"]);
        });
    }
}

?>
