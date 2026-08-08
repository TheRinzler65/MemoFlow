<?php

namespace App\Models;

use JsonSerializable;
use Override;

class Cards extends Model implements JsonSerializable
{
    protected static string $table = 'cards';

    private ?int $id;
    private string $question;
    private string $answer;
    private int $box;
    private string $next_review;
    private ?string $last_review;
    private string $created_at;
    private string $updated_at;
    private int $deck_id;

    public function __construct(string $newQuestion, string $newAnswer, int $newBox, string $newNext, string $newCreatedAt, string $newUpdatedAt, int $newDeckId, ?string $newLast = null, ?int $newId = null)
    {
        $this->id = $newId;
        $this->setQuestion($newQuestion);
        $this->setAnswer($newAnswer);
        $this->setBox($newBox);
        $this->setNextReview($newNext);
        $this->setCreatedAt($newCreatedAt);
        $this->setUpdatedAt($newUpdatedAt);
        $this->setDeckId($newDeckId);
        $this->setLastReview($newLast);
    }

    public function jsonSerialize(): array
    {
        return [
            'id'          => $this->id,
            'question'    => $this->question,
            'answer'      => $this->answer,
            'box'         => $this->box,
            'next_review' => $this->next_review,
            'last_review' => $this->last_review,
            'deck_id'     => $this->deck_id,
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): string
    {
        return $this->question;
    }

    public function getAnswer(): string
    {
        return $this->answer;
    }

    public function getBox(): int
    {
        return $this->box;
    }

    public function getNextReview(): string
    {
        return $this->next_review;
    }

    public function getLastReview(): ?string
    {
        return $this->last_review;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    public function getDeckId(): int
    {
        return $this->deck_id;
    }

    public function setQuestion(string $newQuestion): void
    {
        $this->question = $newQuestion;
    }

    public function setAnswer(string $newAnswer): void
    {
        $this->answer = $newAnswer;
    }

    public function setBox(int $newBox): void
    {
        $this->box = $newBox;
    }

    public function setNextReview(string $newNextReview): void
    {
        $this->next_review = $newNextReview;
    }

    public function setLastReview(?string $newLastReview): void
    {
        $this->last_review = $newLastReview;
    }

    public function setCreatedAt(string $newCreatedAt): void
    {
        $this->created_at = $newCreatedAt;
    }

    public function setUpdatedAt(string $newUpdatedAt): void
    {
        $this->updated_at = $newUpdatedAt;
    }

    public function setDeckId(int $newDeckId): void
    {
        $this->deck_id = $newDeckId;
    }

    public static function findByDeckId(int $id): ?array
    {
        $db = self::initDb();
        $sql = "SELECT * FROM " . static::$table . " WHERE deck_id = :id;";
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
            $row["question"],
            $row["answer"],
            (int)$row["box"],
            $row["next_review"],
            $row["created_at"],
            $row["updated_at"],
            (int)$row["deck_id"],
            $row["last_review"],
            (int)$row["id"]
        );
    }

    public function insert(): array
    {
        $db = self::initDb();
        $sql = "INSERT INTO " . static::$table . " 
                (question, answer, box, next_review, created_at, updated_at, deck_id) 
                VALUES (:question, :answer, :box, :next_review, :created_at, :updated_at, :deck_id);";

        $stmt = $db->prepare($sql);
        $success = $stmt->execute([
            ':question'    => $this->question,
            ':answer'      => $this->answer,
            ':box'         => $this->box,
            ':next_review' => $this->next_review,
            ':created_at'  => $this->created_at,
            ':updated_at'  => $this->updated_at,
            ':deck_id'     => $this->deck_id
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
        $db = self::initDb();
        $sql = "UPDATE " . static::$table . " 
                SET question = :question, 
                    answer = :answer, 
                    box = :box, 
                    next_review = :next_review, 
                    last_review = :last_review, 
                    updated_at = :updated_at, 
                    deck_id = :deck_id 
                WHERE id = :id;";
        
        $stmt = $db->prepare($sql);
        
        $success = $stmt->execute([
            ':id'          => $this->id,
            ':question'    => $this->question,
            ':answer'      => $this->answer,
            ':box'         => $this->box,
            ':next_review' => $this->next_review,
            ':last_review' => $this->last_review,
            ':updated_at'  => $this->updated_at,
            ':deck_id'     => $this->deck_id
        ]);

        if ($success) {
            return ["status" => "success"];
        }

        return [
            "status" => "error", 
            "message" => "Erreur lors de la modification de la carte.", 
            "code" => 500
        ];
    }
}
