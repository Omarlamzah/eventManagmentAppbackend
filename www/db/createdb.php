<?php

// SQLite database file path
$database_file = 'db.db';

try {
    // Connect to SQLite database (create it if it doesn't exist)
    $pdo = new PDO("sqlite:$database_file");

    // Set error mode to exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL statement to create the "user" table
    $sql_user = "
    CREATE TABLE IF NOT EXISTS user (
        ID INTEGER PRIMARY KEY AUTOINCREMENT,
        Nom VARCHAR(255),
        Titre VARCHAR(255),
        Date_conferences DATE,
        Horaire TIME,
        Duree_com INT,
        Nom_salle VARCHAR(255),
        Pupitre VARCHAR(255),
        Etat VARCHAR(255),
        Fichier VARCHAR(255),
        profile VARCHAR(255) DEFAULT 'default.png',
        pipiterid VARCHAR(255)
    )";

    // Execute the SQL statement to create the "user" table
    $pdo->exec($sql_user);

    // SQL statement to create the "pipiter" table
    $sql_pipiter = "
    CREATE TABLE IF NOT EXISTS pipiter (
        ID INTEGER PRIMARY KEY AUTOINCREMENT,
        Nom VARCHAR(255)
    )";

    // Execute the SQL statement to create the "pipiter" table
    $pdo->exec($sql_pipiter);

    // SQL statement to create the "salle" table
    $sql_salle = "
    CREATE TABLE IF NOT EXISTS salle (
        ID INTEGER PRIMARY KEY AUTOINCREMENT,
        Nom VARCHAR(255),
        pipiterid VARCHAR(255)
    )";

    // Execute the SQL statement to create the "salle" table
    $pdo->exec($sql_salle);

    echo "Tables 'user', 'pipiter', and 'salle' created successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>
