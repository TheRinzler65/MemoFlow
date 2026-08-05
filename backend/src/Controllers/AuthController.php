<?php

namespace App\Controllers;

use App\Helpers\Error;
use App\Helpers\Json;
use App\Helpers\Session;
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

        try {
            $result = $user->insert();

            if ($result["status"] === "error") {
                Error::sendError($result["message"], $result["code"]);
                return;
            }

            Json::send(["status" => "success"], 201);
        } catch (Exception $e) {
            Error::sendError($e->getMessage(), $e->getCode());
        }
    }

    public function login(): void
    {
        if (isset($_SESSION["user"])) {
            Error::sendError("Déjà connecté", 400);
        }

        $body = $this->getBody();
        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:8'
        ];
        $inputs = Validator::validate($body, $rules);
        $remember = !empty($body['remember']);

        try {
            $user = Users::findByEmail($inputs["email"]);

            if ($user === null || !password_verify($inputs["password"], $user->getPassword())) {
                Error::sendError("Identifiants invalides.", 401);
            }

            session_regenerate_id(true);

            $_SESSION['user'] = ["id" => $user->getId(), "email" => $user->getEmail(), "name" => $user->getName()]; // "role" => $user->getRole()
            $_SESSION['last_activity'] = time();
            $_SESSION['remember'] = $remember;

            if ($remember) {
                setcookie(session_name(), session_id(), [
                    'expires' => time() + Session::TTL_REMEMBER,
                    'path' => '/',
                    'httponly' => true,
                    'samesite' => 'Lax',
                ]);
            }

            Json::send(["status" => "success", "user" => $_SESSION['user']], 200);
        } catch (Exception $e) {
            Error::sendError($e->getMessage(), $e->getCode());
        }
    }

    public function logout(): void
    {
        if (!isset($_SESSION["user"])) {
            Error::sendError("Il faut être connecté pour se déconnecter.", 400);
        }
        session_destroy();
        Json::send(["status" => "success"], 200);
    }

    public function me(): void
    {
        if (Session::isExpired()) {
            Session::destroy();
            Error::sendError("Session expirée", 401);
        }

        if (isset($_SESSION["user"])) {
            Session::touch();
            Json::send([
                "status" => "success",
                "user" => $_SESSION["user"]
            ], 200);
        } else {
            Error::sendError("Non authentifié", 401);
        }
    }
}
