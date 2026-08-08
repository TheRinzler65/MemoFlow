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
        } else{
            Json::send(["status" => "success", "deck" => $deck], 200);
        }
    }

    public static function create(): void
    {
        
    } 
}
