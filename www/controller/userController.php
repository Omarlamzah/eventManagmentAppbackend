<?php

class UserController {
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
    
    // Create a new user
    public function createUser($Nom, $Titre, $Date_conferences, $Horaire, $Duree_com, $Nom_salle, $Etat, $Fichier, $pipiterid) {
        try {
            $sql = "INSERT INTO user (Nom, Titre, Date_conferences, Horaire, Duree_com, Nom_salle, Etat, Fichier, pipiterid) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$Nom, $Titre, $Date_conferences, $Horaire, $Duree_com, $Nom_salle, $Etat, $Fichier, $pipiterid]);
            return "User created successfully.";
        } catch (PDOException $e) {
            return "Error creating user: " . $e->getMessage();
        }
    }


     // Update a user
     public function updateUser($id, $Nom, $Titre, $Date_conferences, $Horaire, $Duree_com, $Nom_salle, $Etat, $Fichier, $pipiterid) {
        try {
            $sql = "UPDATE user SET Nom=?, Titre=?, Date_conferences=?, Horaire=?, Duree_com=?, Nom_salle=?,  Etat=?, Fichier=?, pipiterid=? WHERE ID=?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$Nom, $Titre, $Date_conferences, $Horaire, $Duree_com, $Nom_salle, $Etat, $Fichier, $pipiterid, $id]);
            return "User updated successfully.";
        } catch (PDOException $e) {
            return "Error updating user: " . $e->getMessage();
        }
    }

    // Delete a user
    public function deleteUser($id) {
        try {
            $sql = "DELETE FROM user WHERE ID=?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            return "User deleted successfully.";
        } catch (PDOException $e) {
            return "Error deleting user: " . $e->getMessage();
        }
    }
    


    
// Upload file for a user
public function uploadUserfile($id, $filename) {
    try {
        // Update the file path in the database
        $sql = "UPDATE user SET Fichier=? WHERE ID=?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$filename, $id]);

        return "✔  User fiche changed";
    } catch (PDOException $e) {
        return "❌ Error updating user: " . $e->getMessage();
    }
}

// Upload file for a user
public function updateUserstatus($id, $status) {
    try {
        // Update the file path in the database
        $sql = "UPDATE user SET Etat=? WHERE ID=?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$status, $id]);

        return "✔  User Etat changed";
    } catch (PDOException $e) {
        return "❌ Error updating user: " . $e->getMessage();
    }
}



// Upload file for a user
public function uploadUserProfilePicture($id, $filename) {
    try {
        // Update the file path in the database
        $sql = "UPDATE user SET profile=? WHERE ID=?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$filename, $id]);

        return "✔ User profile changed";
    } catch (PDOException $e) {
        return "❌ Error updating user: " . $e->getMessage();
    }
}


    // Read all users  
    public function getAllUsers( ) {
        try {
            $sql = "SELECT * FROM user ";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Error retrieving users: " . $e->getMessage();
        }
    }

   // Read all users with pagination
public function getAllUsersPagination($limit = 50, $offset = 0, $search_query = '') {
    try {
        // Construct the SQL query with an optional search condition
        $sql = "SELECT * FROM user";
        if (!empty($search_query)) {
            $sql .= " WHERE Nom LIKE :search_query OR Titre LIKE :search_query OR Date_conferences LIKE :search_query OR Horaire LIKE :search_query OR Duree_com LIKE :search_query OR Nom_salle LIKE :search_query OR Etat LIKE :search_query OR Fichier LIKE :search_query OR pipiterid LIKE :search_query";
        }
        $sql .= " ORDER BY ID DESC LIMIT :limit OFFSET :offset";
        
        // Prepare the SQL statement
        $stmt = $this->pdo->prepare($sql);

        // Bind parameters
        if (!empty($search_query)) {
            $search_query = '%' . $search_query . '%';
            $stmt->bindParam(':search_query', $search_query, PDO::PARAM_STR);
        }
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        
        // Execute the SQL statement
        $stmt->execute();

        // Fetch and return the results
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return "Error retrieving users: " . $e->getMessage();
    }
}


public function getAllUsersPaginationcount($search_query = '') {
    try {
        // Construct the SQL query with an optional search condition
        $sql = "SELECT * FROM user";
        if (!empty($search_query)) {
            $sql .= " WHERE ID LIKE :search_query OR Nom LIKE :search_query OR Titre LIKE :search_query OR Date_conferences LIKE :search_query OR Horaire LIKE :search_query OR Duree_com LIKE :search_query OR Nom_salle LIKE :search_query OR Etat LIKE :search_query OR Fichier LIKE :search_query OR pipiterid LIKE :search_query";
        }
        $sql .= " ORDER BY ID DESC";
        
        // Prepare the SQL statement
        $stmt = $this->pdo->prepare($sql);

        // Bind parameters
        if (!empty($search_query)) {
            $search_query = '%' . $search_query . '%';
            $stmt->bindParam(':search_query', $search_query, PDO::PARAM_STR);
        }
        
        // Execute the SQL statement
        $stmt->execute();

        // Fetch and return the results
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return "Error retrieving users: " . $e->getMessage();
    }
}


    












// Read all users with pagination and search
public function getAllUsersPaginationWithsearch($limit = 50, $offset = 0, $search_text = '', $search_date = '', $search_time = '', $pipiter_id = 'noselected') {
    try {
        // Construct the base SQL query
        $sql = "SELECT * FROM user";

        // Prepare an array to store conditions
        $conditions = [];

        // Prepare an array to store parameter bindings
        $bindings = [];
       
       
        // Check if search text is provided
            if (!empty($search_text)) {
                // Add conditions to search in Nom, Titre, Fichier, and other specified fields
                $conditions[] = "(Nom LIKE :search_text OR Titre LIKE :search_text OR Fichier LIKE :search_text OR ID LIKE :search_text OR Horaire LIKE :search_text OR Duree_com LIKE :search_text OR Nom_salle LIKE :search_text OR Etat LIKE :search_text)";
                $bindings[':search_text'] = '%' . $search_text . '%';
            }


        // Check if search date is provided
        if (!empty($search_date)) {
            $conditions[] = "Date_conferences = :search_date";
            $bindings[':search_date'] = $search_date;
        }

        // Check if search time is provided
        if (!empty($search_time)) {
            $conditions[] = "Horaire = :search_time";
            $bindings[':search_time'] = $search_time;
        }

        // Check if pipiter ID is selected
        if ($pipiter_id != 'noselected') {
            $conditions[] = "pipiterid = :pipiter_id";
            $bindings[':pipiter_id'] = $pipiter_id;
        }

        // Check if any conditions are provided
        if (!empty($conditions)) {
            // Append WHERE clause to SQL query
            $sql .= " WHERE " . implode(" OR ", $conditions);
        }

        // Add ORDER BY, LIMIT, and OFFSET clauses
        $sql .= " ORDER BY ID DESC LIMIT :limit OFFSET :offset";

        // Prepare the SQL statement
        $stmt = $this->pdo->prepare($sql);

        // Bind parameters
        foreach ($bindings as $key => $value) {
            $stmt->bindParam($key, $value);
        }
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

        // Execute the SQL statement
        $stmt->execute();

        // Fetch and return the results
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return "Error retrieving users: " . $e->getMessage();
    }
}

    




// Count users based on search criteria
public function countUsersWithSearch($search_text = '', $search_date = '', $search_time = '', $pipiter_id = 'noselected') {
    try {
        // Construct the base SQL query
        $sql = "SELECT COUNT(*) as total_users FROM user";

        // Prepare an array to store conditions
        $conditions = [];

        // Prepare an array to store parameter bindings
        $bindings = [];

        // Check if search text is provided
        if (!empty($search_text)) {
            $conditions[] = "(Nom LIKE :search_text OR Titre LIKE :search_text)";
            $bindings[':search_text'] = '%' . $search_text . '%';
        }

        // Check if search date is provided
        if (!empty($search_date)) {
            $conditions[] = "Date_conferences = :search_date";
            $bindings[':search_date'] = $search_date;
        }

        // Check if search time is provided
        if (!empty($search_time)) {
            $conditions[] = "Horaire = :search_time";
            $bindings[':search_time'] = $search_time;
        }

        // Check if pipiter ID is selected
        if ($pipiter_id != 'noselected') {
            $conditions[] = "pipiterid = :pipiter_id";
            $bindings[':pipiter_id'] = $pipiter_id;
        }

        // Check if any conditions are provided
        if (!empty($conditions)) {
            // Append WHERE clause to SQL query
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        // Prepare the SQL statement
        $stmt = $this->pdo->prepare($sql);

        // Bind parameters
        foreach ($bindings as $key => $value) {
            $stmt->bindParam($key, $value);
        }

        // Execute the SQL statement
        $stmt->execute();

        // Fetch and return the total count of users
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_users'];
    } catch (PDOException $e) {
        return "Error counting users: " . $e->getMessage();
    }
}

   
}

?>
