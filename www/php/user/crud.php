<?php
require_once "./../../controller/PipiterController.php";

require_once "./../../controller/userController.php";
// SQLite database file path
$database_file = './../../db/db.db';


// Instantiate UserController
$userController = new UserController($database_file);

// Example usage of the functions

// If form submitted for creating a new user
if (isset($_POST['create_user'])) {
    $Nom = $_POST['Nom'];
    $Titre = $_POST['Titre'];
    $Date_conferences = $_POST['Date_conferences'];
    $Horaire = $_POST['Horaire'];
    $Duree_com = $_POST['Duree_com'];
    $Nom_salle = $_POST['Nom_salle'];
    $Pupitre = $_POST['Pupitre'];
    $Etat = $_POST['Etat'];
    $Fichier = $_POST['Fichier'];
    $pipiterid = $_POST['pipiterid'];
    $result = $userController->createUser($Nom, $Titre, $Date_conferences, $Horaire, $Duree_com, $Nom_salle, $Pupitre, $Etat, $Fichier, $pipiterid);
    echo "<p>$result</p>";
}


// If form submitted for uploading a file
if (isset($_POST['IDuser'])) {
    $id = $_POST['IDuser']; // Assuming the hidden input field is named 'IDuser'
    
    // For regular file upload
    if (isset($_FILES['fileupload'])) {
        $uploadedFile = $_FILES['fileupload']; // Access the uploaded file data from $_FILES
        
        // Check if file was uploaded without errors
        if ($uploadedFile['error'] == UPLOAD_ERR_OK) {
            $targetDirectory = "./../../uploads/presentation/"; // Specify the target directory where you want to save the uploaded files
            
            // Generate a unique filename for the uploaded file
            $filename = $id . '_' . basename($uploadedFile['name']);
            $targetPath = $targetDirectory . $filename;
            
            // Move the uploaded file to the target directory
            if (move_uploaded_file($uploadedFile['tmp_name'], $targetPath)) {
                // Call the function to update the file path in the database
                $result = $userController->uploadUserfile($id, $filename);
                echo $result ."<br>"; // Output only the response message
            } else {
                echo "❌ Error uploading file." ."<br>";
            }
        } else {
            echo "❌No file uploaded or an error occurred during upload."."<br>";
        }
    }
    
    // For profile picture upload
    if (isset($_FILES['fileuploadprofile'])) {
        $fileuploadprofile = $_FILES['fileuploadprofile']; // Access the uploaded file data from $_FILES
        
        // Check if profile picture file was uploaded without errors
        if ($fileuploadprofile['error'] == UPLOAD_ERR_OK) {
            $targetDirectory = "./../../uploads/profile/"; // Specify the target directory where you want to save the profile picture files
            
            // Generate a unique filename for the uploaded profile picture
            $profileFilename = $id . '_profile_' . basename($fileuploadprofile['name']);
            $targetProfilePath = $targetDirectory . $profileFilename;
            
            // Move the uploaded profile picture file to the target directory
            if (move_uploaded_file($fileuploadprofile['tmp_name'], $targetProfilePath)) {
                // Call the function to update the profile picture file path in the database
                $result = $userController->uploadUserProfilePicture($id, $profileFilename);
                echo $result ."<br>"; // Output only the response message
            } else {
                echo "❌ Error uploading profile picture file." ."<br>";
            }
        } else {
            echo "❌No profile picture file uploaded or an error occurred during upload." ."<br>";
        }
    }
}

// If form submitted for updating a user
if (isset($_POST['update_user'])) {
    $id = $_POST['id'];
    $Nom = $_POST['Nom'];
    $Titre = $_POST['Titre'];
    $Date_conferences = $_POST['Date_conferences'];
    $Horaire = $_POST['Horaire'];
    $Duree_com = $_POST['Duree_com'];
    $Nom_salle = $_POST['Nom_salle'];
    $Pupitre = $_POST['Pupitre'];
    $Etat = $_POST['Etat'];
    $Fichier = $_POST['Fichier'];
    $pipiterid = $_POST['pipiterid'];
    $result = $userController->updateUser($id, $Nom, $Titre, $Date_conferences, $Horaire, $Duree_com, $Nom_salle, $Pupitre, $Etat, $Fichier, $pipiterid);
    echo "<p>$result</p>";
}

// If form submitted for deleting a user
if (isset($_POST['delete_user'])) {
    $id = $_POST['id'];
    $result = $userController->deleteUser($id);
    echo "<p>$result</p>";
}

// Pagination variables
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = 2;
$offset = ($page - 1) * $limit;

// Get total number of users
$total_users = count($userController->getAllUsers());

// Calculate total pages
$total_pages = ceil($total_users / $limit);

// Get users for the current page
$users = $userController->getAllUsersPagination($limit, $offset);

?>