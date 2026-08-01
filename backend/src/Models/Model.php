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
     * 
     * @return static[] Un tableau d'instances de la classe appelante
     */
    public static function findAll(): array
    {
        $db = self::initDb();
        $stmt = $db->query("SELECT * FROM " . static::$table);
        $results = $stmt->fetchAll(PDO::FETCH_CLASS, static::class);

        if (!$results) {
            return [];
        }

        return $results;
    }
}
