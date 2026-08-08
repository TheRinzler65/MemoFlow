<?php

namespace App\Models;

use Exception;
use JsonSerializable;
use Override;
use PDOException;

class Decks extends Model implements JsonSerializable
{
    protected static string $table = 'decks';

    private ?int $id;
    private string $title;
    private ?string $description;
    private ?string $created_at;
    private int $user_id;

    public function __construct(string $newTitle, int $newUserId, ?string $newDescription = null, ?string $newCreatedAt = null, ?int $newId = null)
    {
        $this->id = $newId;
        $this->setTitle($newTitle);
        $this->setDescription($newDescription);
        $this->setCreatedAt($newCreatedAt);
        $this->setUserId($newUserId);
    }

    public function jsonSerialize(): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'created_at'  => $this->created_at,
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setTitle(string $newTitle): void
    {
        $this->title = $newTitle;
    }

    public function setDescription(?string $newDescription): void
    {
        $this->description = $newDescription;
    }

    public function setCreatedAt(?string $newCreatedAt): void
    {
        $this->created_at = $newCreatedAt;
    }

    public function setUserId(int $newUserId): void
    {
        $this->user_id = $newUserId;
    }

    #[Override]
    public static function findAll(): array
    {
        $rows = parent::findAll();

        $objects = [];

        foreach ($rows as $row) {
            $object = new Decks(
                $row["title"],
                (int)$row["user_id"],
                $row["description"],
                $row["created_at"],
                (int)$row["id"]
            );

            $objects[] = $object;
        }

        return $objects;
    }

    #[Override]
    public static function findById(int $id): ?Decks
    {
        $result =  parent::findById($id);

        if ($result === null) return null;

        $deck = new Decks($result["title"], (int)$result["user_id"], $result["description"], $result["created_at"], (int)$result["id"]);
        return $deck;
    }

    public function insert(): array
    {
        $db = self::initDb();
        $sql = "INSERT INTO " . static::$table . " (title, description, user_id) VALUES (:title, :description, :user_id);";
        $stmt = $db->prepare($sql);

        try {

            $stmt->execute([
                ':title' => $this->title,
                ':description' => $this->description,
                ':user_id' => $this->user_id,
            ]);

            return ["status" => "success"];
        } catch (Exception $e) {

            return ["status" => "error", "message" => "Something went wrong.", "code" => 500];
        }
    }

    public function update(): array
    {
        $db = self::initDb();
        $sql = "UPDATE " . static::$table . " SET title = :title, description = :description, user_id = :user_id WHERE id = :id;";
        $stmt = $db->prepare($sql);

        try {

            $stmt->execute([
                ':title' => $this->title,
                ':description' => $this->description,
                ':user_id' => $this->user_id,
                ':id' => $this->id
            ]);

            return ["status" => "success"];
        } catch (Exception $e) {

            return ["status" => "error", "message" => "Something went wrong.", "code" => 500];
        }
    }
}
