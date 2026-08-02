<?php

namespace App\Controllers;

use App\Helpers\Error;
use App\Helpers\Json;
use App\Helpers\Response;
use App\Helpers\Validator;
use App\Models\Users;

class AuthController extends Controller
{
    public function register(): void
    {
        $body = $this->getBody();
        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'confirm' => 'required|matches:password'
        ];
        $inputs = Validator::validate($body, $rules);

        $user = new Users($inputs["name"], $inputs["email"], $inputs["password"]);

        $result = $user->insert();

        if ($result["status"] === "error") {
            Error::sendError($result["message"], $result["code"]);
            return;
        }

        Json::send(["status" => "success"], 201);
    }

    public function login(): void
    {
        $body = $this->getBody();
        // $this->requireFields($body, ['email', 'password']);
        // -> findByEmail -> password_verify -> $_SESSION -> Response::send
    }

    public function logout(): void
    {
        // TODO : Deconnection
    }
}
