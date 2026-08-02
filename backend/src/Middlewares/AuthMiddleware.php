<?php

namespace App\Middlewares;

class AuthMiddleware
{
  public function handle(): void
  {
    if (!isset($_SESSION['user'])) {
      header('Location: /sign-up');
      exit;
    }
  }
}
