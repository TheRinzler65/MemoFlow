<?php

namespace App\Controllers;

use App\Helpers\Error;

abstract class Controller
{
    protected function getBody(): array
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);

        return \is_array($data) ? $data : [];
    }
}
