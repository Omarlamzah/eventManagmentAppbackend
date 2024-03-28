<?php

class SalleController {
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

    // Create a new salle
    public function createSalle($Nom, $pipiterid) {
        try {
            $sql = "INSERT INTO salle (Nom, pipiterid) VALUES (?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$Nom, $pipiterid]);
            return "Salle created successfully.";
        } catch (PDOException $e) {
            return "Error creating salle: " . $e->getMessage();
        }
    }

    // Read all salles
    public function getAllSalles() {
        try {
            $sql = "SELECT * FROM salle";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Error retrieving salles: " . $e->getMessage();
        }
    }

    // Update a salle
    public function updateSalle($id, $Nom, $pipiterid) {
        try {
            $sql = "UPDATE salle SET Nom=?, pipiterid=? WHERE ID=?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$Nom, $pipiterid, $id]);
            return "Salle updated successfully.";
        } catch (PDOException $e) {
            return "Error updating salle: " . $e->getMessage();
        }
    }

    // Delete a salle
    public function deleteSalle($id) {
        try {
            $sql = "DELETE FROM salle WHERE ID=?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            return "Salle deleted successfully.";
        } catch (PDOException $e) {
            return "Error deleting salle: " . $e->getMessage();
        }
    }
}

?>
