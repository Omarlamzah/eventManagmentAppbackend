<?php

class PipiterController {
    private $pdo;

    public function __construct($database_file) {
        try {
            // Connect to SQLite database (create it if it doesn't exist)
            $this->pdo = new PDO("sqlite:$database_file");
            // Set error mode to exceptions
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    // Create a new pipiter
    public function createPipiter($Nom) {
        try {
            $sql = "INSERT INTO pipiter (Nom) VALUES (?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$Nom]);
            return "Pipiter created successfully.";
        } catch (PDOException $e) {
            return "Error creating pipiter: " . $e->getMessage();
        }
    }

    // Read all pipiters
    public function getAllPipiters() {
        try {
            $sql = "SELECT * FROM pipiter";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Error retrieving pipiters: " . $e->getMessage();
        }
    }

    // Update a pipiter
    public function updatePipiter($id, $Nom) {
        try {
            $sql = "UPDATE pipiter SET Nom=? WHERE ID=?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$Nom, $id]);
            return "Pipiter updated successfully.";
        } catch (PDOException $e) {
            return "Error updating pipiter: " . $e->getMessage();
        }
    }

    // Delete a pipiter
    public function deletePipiter($id) {
        try {
            $sql = "DELETE FROM pipiter WHERE ID=?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            return "Pipiter deleted successfully.";
        } catch (PDOException $e) {
            return "Error deleting pipiter: " . $e->getMessage();
        }
    }
}

?>
