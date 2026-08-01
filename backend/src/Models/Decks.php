<?php

namespace App\Models;

use JsonSerializable;

class Decks extends Model implements JsonSerializable
{
    protected static string $table = 'decks';

    private int $id;
    private string $title;
    private string $description;
    private string $created_at;
    private int $user_id;

    public function jsonSerialize(): array
    {
        return [
            'id'    => $this->id,
            'title'  => $this->title,
            'description' => $this->description,
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCreatedAt(): string
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

    public function setDescription(string $newDescription): void
    {
        $this->description = $newDescription;
    }

    public function setCreatedAt(string $newCreatedAt): void
    {
        $this->created_at = $newCreatedAt;
    }

    public function setUserId(string $newUserId): void
    {
        $this->user_id = $newUserId;
    }
}
