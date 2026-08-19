<?php

namespace App\Controllers;

use App\Helpers\Error;
use App\Helpers\Json;
use App\Helpers\Validator;
use App\Models\Cards;
use App\Models\Reviews;
use DateTime;
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

    public static function stats(int $id): void
    {
        Json::send([], 200); // TODO
    }

    public function review(int $id): void
    {
        $body = $this->getBody();
        $rules = [
            'success' => 'boolean'
        ];
        $inputs = Validator::validate($body, $rules);

        // Vérifier si review existe dans bdd
        try {
            // L'id reçu est l'id de la carte
            $card = Cards::findById($id);

            if (!$card) {
                Error::sendError("Carte introuvable.", 404);
            }

            // Chercher review liée à la card
            $reviews = Reviews::findByCardId($id);
            $review = $reviews[0] ?? null;

            // Si aucune review alors en créer une avec la box 1
            if ($review === null) {
                $review = new Reviews(false, 0, 1, $id);
                $review->setReviewedAt(date("Y-m-d H:i:s"));

                $result = $review->insert();

                if (isset($result["status"]) && $result["status"] === "error") {
                    Error::sendError($result["message"], $result["code"] ?? 500);
                    return;
                }
            }

            // Vérifier si user bonne réponse ou non
            $success = $inputs['success'];
            if ($success) {
                // Si oui alors -> prochaine box
                $review->setPreviousBox($review->getNewBox());
                $review->setNewBox($review->getNewBox() + 1);
            } else {
                // Sinon retour box 1
                $review->setPreviousBox(0);
                $review->setNewBox(1);
            }

            $review->setSuccess($success);

            // Puis enregistrer dans bdd
            $result = $review->update();

            if (isset($result["status"]) && $result["status"] === "error") {
                Error::sendError($result["message"], $result["code"] ?? 500);
                return;
            }

            // Récupérer la date du jour et l'inserer dans last_review
            $now = date("Y-m-d H:i:s");
            $card->setLastReview($now);

            // Grâce à new_box calculer le rythme : $rythme = 2**(new_box-1)
            $rythme = 2 ** ($review->getNewBox() - 1);

            // Enfin, calculer next_review grâce au code envoyé.
            $date = new DateTime($now);

            $date->modify("+" . $rythme . " days"); // exemple "+64 days"

            $futureDate = $date->format("Y-m-d H:i:s");
            $card->setNextReview($futureDate);

            $result = $card->update();

            if (isset($result["status"]) && $result["status"] === "error") {
                Error::sendError($result["message"], $result["code"] ?? 500);
                return;
            }

            Json::send([
                "status" => "success",
                "message" => "Review modifiée avec succès"
            ], 200);
        } catch (Exception $e) {
            Error::sendError($e->getMessage(), 500);
        }
    }

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
