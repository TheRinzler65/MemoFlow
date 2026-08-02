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
    private ?string $last_review;
    private string $created_at;
    private string $updated_at;
    private int $deck_id;

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
}