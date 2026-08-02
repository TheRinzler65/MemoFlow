<?php

namespace App\Controllers;

use App\Helpers\Error;

abstract class Controller
{
    protected function getBody(): array
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);

        return is_array($data) ? $data : [];
    }

    protected function requireFields(array $body, array $fields): void
    {
        foreach ($fields as $field) {
            if (!isset($body[$field]) || trim((string) $body[$field]) === '') {
                Error::sendError("Le champ '$field' est requis", 422);
            }
        }
    }
}
