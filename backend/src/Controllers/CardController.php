<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Helpers\Error;
use App\Helpers\Json;
use App\Helpers\Validator;
use App\Models\Cards;
use Exception;

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
      Error::sendError("Aucune carte trouvée dans ce jeu.", 400);
    }

    Json::send(["status" => "success", "cards" => $cards], 200);
  }

  public function create(): void
  {
    $body = $this->getBody();
    $rules = [
      'question' => 'required|min:1',
      'answer' => 'required|min:1',
      'deck_id' => 'required'
    ];
    $inputs = Validator::validate($body, $rules);

    $now = date("Y-m-d H:i:s");
    $initialBox = 1;

    $card = new Cards(
      $inputs['question'],
      $inputs['answer'],
      $initialBox,
      $now, // next_review
      $now, // created_at
      $now, // updated_at
      (int)$inputs['deck_id']
    );

    try {
      $result = $card->insert();

      if (isset($result["status"]) && $result["status"] === "error") {
        Error::sendError($result["message"], $result["code"] ?? 500);
      }

      Json::send([
        "status" => "success",
        "message" => "Carte créée avec succès"
      ], 201);
    } catch (Exception $e) {
      Error::sendError($e->getMessage(), $e->getCode());
    }
  }

  public function edit(int $id): void
  {
    $body = $this->getBody();
    $rules = [
      'question' => 'required|min:1',
      'answer'   => 'required|min:1'
    ];
    $inputs = Validator::validate($body, $rules);

    try {
      $card = Cards::findById($id);

      if (!$card) {
        Error::sendError("Carte introuvable.", 404);
      }

      $card->setQuestion($inputs['question']);
      $card->setAnswer($inputs['answer']);
      $card->setUpdatedAt(date('Y-m-d H:i:s'));

      $result = $card->update();

      if (isset($result["status"]) && $result["status"] === "error") {
        Error::sendError($result["message"], $result["code"] ?? 500);
        return;
      }

      Json::send([
        "status" => "success",
        "message" => "Carte modifiée avec succès"
      ], 200);
    } catch (Exception $e) {
      Error::sendError($e->getMessage(), 500);
    }
  }

  public function remove(int $id): void
  {
    try {

      $card = Cards::findById($id);

      if (!$card) {
        Error::sendError("Carte introuvable.", 404);
      }

      $result = $card->delete();

      if (isset($result["status"]) && $result["status"] === "error") {
        Error::sendError($result["message"], $result["code"] ?? 500);
        return;
      }

      Json::send([
        "status" => "success",
        "message" => "Carte supprimée avec succès"
      ], 200);
    } catch (Exception $e) {
      Error::sendError($e->getMessage(), 500);
    }
  }
}
