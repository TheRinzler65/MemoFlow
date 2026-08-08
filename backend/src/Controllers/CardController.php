<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Helpers\Error;
use App\Helpers\Json;
use App\Models\Cards;

class CardController extends Controller
{
  public function index(): void
  {
    $cards = Cards::findAll();
    Json::send(["status" => "success", "cards" => $cards], 200);
  }

  public function getByDeckId(int $id): void
  {
    $cards = Cards::findByDeckId($id);

    if (!$cards) {
      Error::sendError("Aucune carte trouvé dans ce jeu.", 400);
    }

    Json::send(["status" => "success", "cards" => $cards], 200);
  }
}
