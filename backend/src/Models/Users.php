<?php

namespace App\Models;

class Users extends Model
{

    protected static string $table = 'users';

    private int $id;
    private string $email;
    private string $password;
    private string $username;

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setEmail(string $newEmail): void
    {
        $this->email = $newEmail;
    }

    public function setPassword(string $newPassword): void
    {
        $this->password = $newPassword;
    }

    public function setUsername(string $newUsername): void
    {
        $this->username = $newUsername;
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
