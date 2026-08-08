<?php

namespace App\Controllers;

use App\Helpers\Error;
use App\Helpers\Json;
use App\Helpers\Validator;
use App\Models\Decks;
use Exception;
use JsonException;

class DeckController extends Controller
{
    public function index(): void
    {
        $decks = Decks::findAll();
        Json::send(["status" => "success", "decks" => $decks], 200);
    }

    public static function show(int $id): void
    {
        $deck = Decks::findById($id);

        if ($deck === null) {
            Error::sendError("Pas de deck trouvé avec cet id", 400);
        } else {
            Json::send(["status" => "success", "deck" => $deck], 200);
        }
    }

    public function create(): void
    {
        $body = $this->getBody();
        $rules = [
            'title' => 'required',
            'description' => 'min:10',
            'user_id' => 'required',
        ];
        $inputs = Validator::validate($body, $rules);

        $deck = new Decks($inputs["title"], $inputs["user_id"], $inputs["description"]);

        try {
            $result = $deck->insert();

            if ($result["status"] === "error") {
                Error::sendError($result["message"], $result["code"]);
                return;
            }

            Json::send(["status" => "success"], 201);
        } catch (Exception $e) {
            Error::sendError($e->getMessage(), $e->getCode());
        }
    }

    public function edit(int $id): void
    {
        $body = $this->getBody();
        $rules = [
            'title' => 'required',
            'description' => 'min:10',
        ];
        $inputs = Validator::validate($body, $rules);

        try {

            $deck = Decks::findById($id);

            if (!$deck) {
                Error::sendError("Deck introuvable.", 404);
            }

            $deck->setTitle($inputs["title"]);
            $deck->setDescription($inputs["description"]);

            $result = $deck->update();

            if ($result["status"] === "error") {
                Error::sendError($result["message"], $result["code"]);
                return;
            }

            Json::send(["status" => "success"], 200);
        } catch (Exception $e) {
            Error::sendError($e->getMessage(), $e->getCode());
        }
    }

    public function remove(int $id): void
    {
        try {

            $deck = Decks::findById($id);

            if (!$deck) {
                Error::sendError("Deck introuvable.", 404);
            }

            $result = $deck->delete();

            if ($result["status"] === "error") {
                Error::sendError($result["message"], $result["code"]);
                return;
            }

            Json::send(["status" => "success"], 200);
        } catch (Exception $e) {
            Error::sendError($e->getMessage(), $e->getCode());
        }
    }
}
