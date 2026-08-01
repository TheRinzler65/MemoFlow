<?php

namespace App\Controllers;

use App\Models\Users;

class AdminController
{
    public function users()
    {
        $users = Users::findAll();
        $user = Users::findByEmail("prout@caca.com");

        header('Content-Type: application/json');
        echo json_encode($user->getPassword());
    }
}
