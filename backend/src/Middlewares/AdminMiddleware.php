<?php

namespace App\Middlewares;

class AdminMiddleware
{
  public function handle(): void
  {
    if ($_SESSION['user']['role'] !== 'admin') {
      header('HTTP/1.0 403 Forbidden');
      echo "Accès interdit - Tu n'es pas un admin.";
      exit;
    }
  }
}
