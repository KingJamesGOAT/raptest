<?php
// src/Database.php

class Database {
    private $pdo;

    public function __construct() {
        // Lire le fichier INI
        $config = parse_ini_file(__DIR__ . '/database.ini', true);

        if (!$config || !isset($config['database'])) {
            throw new Exception("Erreur : fichier de configuration invalide ou manquant.");
        }

        $db = $config['database'];
        $host = $db['host'];
        $port = $db['port'];
        $dbname = $db['dbname'];
        $username = $db['username'];
        $password = $db['password'];

        try {
            $this->pdo = new PDO(
                "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
                $username,
                $password
            );
        } catch (PDOException $e) {
            die("❌ Erreur de connexion : " . $e->getMessage());
        }
    }

    public function getPdo(): PDO {
        return $this->pdo;
    }
}



//Code pour tester la connexion à la db
// function getDatabaseConnection() {
//     // Lire le fichier INI
//     $config = parse_ini_file(__DIR__ . '/Database.ini', true);

//     // Récupérer les valeurs de la section [database]
//     $db = $config['database'];

//     $host = $db['host'];
//     $port = $db['port'];
//     $dbname = $db['dbname'];
//     $username = $db['username'];
//     $password = $db['password'];

//     try {
//         $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
//         return $pdo;
//     } catch (PDOException $e) {
//         die("❌ Erreur de connexion : " . $e->getMessage());
//     }

