<?php

namespace App\Helpers;

class Error
{
    public static function sendError(string $message, int $statusCode = 500): void
    {
        $mode = Env::get_env_var("MODE");

        if ($mode === 'prod') {
            $message = 'Something went wrong.';
        }

        Json::send([
            'status'  => 'error',
            'message' => $message,
        ], $statusCode);
    }

    public static function sendErrors(array $messages, int $statusCode = 500): void
    {
        $mode = Env::get_env_var("MODE");

        if ($mode === 'prod') {
            $messages = ['Something went wrong.'];
        }

        Json::send([
            'status'  => 'error',
            'messages' => $messages,
        ], $statusCode);
    }
}
