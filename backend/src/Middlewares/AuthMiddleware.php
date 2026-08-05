<?php

namespace App\Middlewares;

use App\Helpers\Error;
use App\Helpers\Session;

class AuthMiddleware
{
  public function handle(): void
  {
    if (Session::isExpired()) {
      Session::destroy();
      Error::sendError("Session expirée", 401);
    }

    if (!isset($_SESSION['user'])) {
      Error::sendError('Non authentifié', 401);
    }

    Session::touch();
  }
}
