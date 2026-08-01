<?php

namespace App\Models;

use JsonSerializable;

class Users extends Model implements JsonSerializable
{

    protected static string $table = 'users';

    private int $id;
    private string $name;
    private string $email;
    private string $password;
    private string $created_at;

    public function jsonSerialize(): array
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'email' => $this->email,
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function setEmail(string $newEmail): void
    {
        $this->email = $newEmail;
    }

    public function setPassword(string $newPassword): void
    {
        $this->password = $newPassword;
    }

    public function setName(string $newName): void
    {
        $this->name = $newName;
    }

    public function setCreatedAt(string $newCreatedAt): void
    {
        $this->created_at = $newCreatedAt;
    }

    public static function findByEmail(string $email): ?Users
    {
        $db = self::initDb();
        $sql = "SELECT * FROM " . static::$table . " WHERE email = :param1;";
        $stmt = $db->prepare($sql);

        $stmt->execute([
            ':param1' => $email
        ]);

        $result = $stmt->fetchObject(static::class);

        if ($result === false) {
            return null;
        }

        return $result;
    }
}
