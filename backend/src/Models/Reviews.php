<?php

namespace App\Models;

use JsonSerializable;

class Reviews extends Model implements JsonSerializable
{
    protected static string $table = 'reviews';

    private int $id;
    private string $reviewed_at;
    private bool $success;
    private int $previous_box;
    private int $new_box;
    private int $card_id;

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
}