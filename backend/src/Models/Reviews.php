<?php

namespace App\Models;

use Exception;
use JsonSerializable;
use Override;

class Reviews extends Model implements JsonSerializable
{
    protected static string $table = 'reviews';

    private int $id;
    private ?string $reviewed_at;
    private bool $success;
    private int $previous_box;
    private int $new_box;
    private int $card_id;

    public function __construct(bool $newSuccess, int $newPreviousBox, int $newBox, int $newCardId, ?string $newReviewedAt = null, ?int $newId = null)
    {
        $this->id = $newId;
        $this->setReviewedAt($newReviewedAt);
        $this->setSuccess($newSuccess);
        $this->setPreviousBox($newPreviousBox);
        $this->setNewBox($newBox);
        $this->setCardId($newCardId);
    }

    public function jsonSerialize(): array
    {
        return [
            'id'           => $this->id,
            'reviewed_at'  => $this->reviewed_at,
            'success'      => $this->success,
            'previous_box' => $this->previous_box,
            'new_box'      => $this->new_box,
            'card_id'      => $this->card_id,
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getReviewedAt(): string
    {
        return $this->reviewed_at;
    }

    public function getSuccess(): bool
    {
        return $this->success;
    }

    public function getPreviousBox(): int
    {
        return $this->previous_box;
    }

    public function getNewBox(): int
    {
        return $this->new_box;
    }

    public function getCardId(): int
    {
        return $this->card_id;
    }

    public function setReviewedAt(string $newReviewedAt): void
    {
        $this->reviewed_at = $newReviewedAt;
    }

    public function setSuccess(bool $newSuccess): void
    {
        $this->success = $newSuccess;
    }

    public function setPreviousBox(int $newPreviousBox): void
    {
        $this->previous_box = $newPreviousBox;
    }

    public function setNewBox(int $newNewBox): void
    {
        $this->new_box = $newNewBox;
    }

    public function setCardId(int $newCardId): void
    {
        $this->card_id = $newCardId;
    }

    public static function findByCardId(int $id): ?array
    {
        $db = self::initDb();
        $sql = "SELECT * FROM " . static::$table . " WHERE card_id = :id;";
        $stmt = $db->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (empty($rows)) {
            return null;
        }

        return array_map([static::class, 'hydrate'], $rows);
    }

    #[Override]
    protected static function hydrate(array $row): static
    {
        return new self(
            $row["reviewed_at"],
            $row["success"],
            $row["previous_box"],
            $row["new_box"],
            (int)$row["card_id"],
            (int)$row["id"]
        );
    }

    public function insert(): array
    {
        $db = self::initDb();
        $sql = "INSERT INTO " . static::$table . " 
                (reviewed_at, success, previous_box, new_box, card_id) 
                VALUES (:reviewed_at, :success, :previous_box, :new_box, :card_id);";

        $stmt = $db->prepare($sql);
        $success = $stmt->execute([
            ':reviewed_at'  => $this->reviewed_at,
            ':success'      => $this->success,
            ':previous_box' => $this->previous_box,
            ':new_box'      => $this->new_box,
            ':card_id'      => $this->card_id
        ]);

        if ($success) {
            $this->id = (int)$db->lastInsertId();
            return ["status" => "success"];
        }

        return [
            "status" => "error",
            "message" => "Erreur lors de la création de la carte.",
            "code" => 500
        ];
    }

    public function update(): array
    {
        try {
            $db = self::initDb();
            $sql = "UPDATE " . static::$table . " SET reviewed_at = :reviewed_at, success = :success, previous_box = :previous_box, new_box = :new_box, card_id = :card_id WHERE id = :id;";
            $stmt = $db->prepare($sql);

            $stmt->execute([
                ':id'           => $this->id,
                ':reviewed_at'  => $this->reviewed_at,
                ':success,'     => $this->success,
                ':previous_box' => $this->previous_box,
                ':new_box'      => $this->new_box,
                ':card_id'      => $this->card_id
            ]);

            return ["status" => "success"];
        } catch (Exception $e) {

            return ["status" => "error", "message" => "Something went wrong.", "code" => 500];
        }
    }

    public function delete(): array
    {
        $db = self::initDb();
        $deleteReviewSql = "DELETE FROM " . static::$table . " WHERE id = :id;";
        $stmt = $db->prepare($deleteReviewSql);

        $success = $stmt->execute([
            ':id' => $this->id
        ]);

        if ($success) {
            return ["status" => "success"];
        }

        return [
            "status" => "error",
            "message" => "Erreur lors de la suppression de la carte.",
            "code" => 500
        ];
    }
}
