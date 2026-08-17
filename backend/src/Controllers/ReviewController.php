<?php

namespace App\Controllers;

use App\Helpers\Error;
use App\Helpers\Json;
use App\Models\Cards;
use App\Models\Reviews;
use Exception;

class ReviewController extends Controller
{
    public function index(): void
    {
        $reviews = Reviews::findAll();
        Json::send(["status" => "success", "reviews" => $reviews], 200);
    }

    public static function today(): void
    {
        $cards = Cards::findCardByDay();
        Json::send(["status" => "success", "cards" => $cards], 200);
    }

    public static function stats(int $id): void {}

    public static function reviews(int $id): void {}

    public function remove(int $id): void
    {
        try {
            $review = Reviews::findById($id);

            if (!$review) {
                Error::sendError("Review introuvable.", 404);
            }

            $result = $review->delete();

            if (isset($result["status"]) && $result["status"] === "error") {
                Error::sendError($result["message"], $result["code"] ?? 500);
                return;
            }
        } catch (Exception $e) {
            Error::sendError($e->getMessage(), 500);
        }
    }
}
