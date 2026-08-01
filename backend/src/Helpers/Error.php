<?php

namespace App\Helpers;

class Error
{
    public static function sendJsonError(string $message, int $statusCode = 500): void
    {
        $mode = Env::get_env_var("MODE");
        
        if ($mode === 'prod') {
            $message = 'Something went wrong.';
        }

        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['status' => 'error', 'message' => $message]);
        exit;
    }
}
