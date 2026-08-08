<?php

namespace App\Models;

use App\Config\Database;
use PDO;

abstract class Model
{
    protected static ?PDO $db = null;
    protected static string $table;
    protected static string $primaryKey = 'id';
    protected static function initDb(): PDO
    {
        if (self::$db === null) {
            self::$db = Database::getInstance()->getConnection();
        }
        return self::$db;
    }

    /**
     * Récupère tous les enregistrements.
     */
    public static function findAll(): array
    {
        $db = self::initDb();
        $stmt = $db->query("SELECT * FROM " . static::$table);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([static::class, 'hydrate'], $rows);
    }

    /**
     * Récupère un enregistrement par son ID
     * 
     * @param int $id
     * @return static|null Retourne l'instance de la classe enfant (ex: Cards) ou null
     */
    public static function findById(int $id): ?static
    {
        $db = self::initDb();
        $sql = "SELECT * FROM " . static::$table . " WHERE id = :id;";
        $stmt = $db->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        return static::hydrate($row);
    }

    protected static function hydrate(array $row): static
    {
        $obj = new static();
        foreach ($row as $key => $value) {
            $obj->$key = $value;
        }
        return $obj;
    }
}
