<?php

abstract class FactoryBase
{
    protected function dbConnect()
    {
        // Charger la configuration depuis le fichier PHP
        $configPath = __DIR__ . "/../../../config/config_data.php";
        if (!file_exists($configPath)) {
            die("⚠️ Le site n'est pas encore installé. Veuillez exécuter install.php.");
        }

        $config = include $configPath;

        $host   = $config["db_host"];
        $port   = $config["db_port"];
        $user   = $config["db_user"];
        $pass   = $config["db_pass"];
        $dbname = $config["db_name"];

        try {
            $pdo = new \PDO(
                "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8",
                $user,
                $pass,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
                ]
            );

            return $pdo;

        } catch (\PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }
}
