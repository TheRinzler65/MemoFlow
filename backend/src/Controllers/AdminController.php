<?php

namespace App\Controllers;

use App\Models\Decks;
use App\Models\Users;

class AdminController
{
    public function users()
    {
        $users = Users::findAll();

        header('Content-Type: application/json');
        echo json_encode($users, JSON_UNESCAPED_UNICODE);
    }

    public function decks()
    {
        $decks = Decks::findAll();

        header('Content-Type: application/json');
        echo json_encode($decks, JSON_UNESCAPED_UNICODE);
    }
}
