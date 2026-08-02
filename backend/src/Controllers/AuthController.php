<?php

namespace App\Controllers;

use App\Helpers\Error;
use App\Helpers\Response;
use App\Models\Users;

class AuthController extends Controller
{
    public function register(): void
    {
        $body = $this->getBody();
        $this->requireFields($body, ['name', 'email', 'password']);
        // + check email valide, + password assez long
        // + confirm si tu le gardes (matches password)
        // -> unicité email -> hash -> insert -> Response::send(..., 201)
    }

    public function login(): void
    {
        $body = $this->getBody();
        $this->requireFields($body, ['email', 'password']);
        // -> findByEmail -> password_verify -> $_SESSION -> Response::send
    }

    public function logout(): void
    {
        // TODO : Deconnection
    }
}