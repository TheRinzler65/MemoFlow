<?php

namespace App\Controllers;

use App\Helpers\Error;
use App\Helpers\Json;
use App\Helpers\Validator;
use App\Models\Users;
use Exception;

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
        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:8'
        ];
        $inputs = Validator::validate($body, $rules);

        try {
            $user = Users::findByEmail($inputs["email"]);

            if ($user === null || !password_verify($inputs["password"], $user->getPassword())) {
                Error::sendError("Identifiants invalides.", 401);
            }

            session_regenerate_id(true);

            $_SESSION['user'] = $user->getId();
            // $_SESSION['role'] = $user->getRole(); // plus tard
            Json::send(["status" => "success"], 200);
        } catch (Exception $e) {
            error_log($e->getMessage());
            Error::sendError("Erreur serveur.", 500);
        }
    }

    public function logout(): void
    {
        session_destroy();
        Json::send(["status" => "success"], 200);
    }
}
