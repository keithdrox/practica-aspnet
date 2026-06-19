<?php
// config/database.php

function getPDOConnection(): PDO
{
    $host = 'localhost';
    $dbname = 'mi_tienda';
    $user = 'postgres';
    $password = 'susiislaif1'; // cambia esto

    $dsn = "pgsql:host=$host;dbname=$dbname";

    try {
        $pdo = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        die('Error de conexión a la base de datos: ' . $e->getMessage());
    }
}