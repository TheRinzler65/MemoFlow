<?php

namespace App\Models;

use JsonSerializable;

class Cards extends Model implements JsonSerializable
{
    protected static string $table = 'cards';

    private int $id;
    private string $question;
    private string $answer;
    private int $box;
    private string $next_review;
    private string $last_review;
    private string $created_at;
    private string $updated_at;
    private int $deck_id;

    public function jsonSerialize(): array
    {
        return [];
    }

    public function getId(): int
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

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }
}
