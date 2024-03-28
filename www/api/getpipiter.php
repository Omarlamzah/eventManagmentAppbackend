<?php
 require_once "./../controller/PipiterController.php";

$database_file = './../db/db.db';
 // Get all pipiters
$pipiterController = new PipiterController($database_file);
$pipiters = $pipiterController->getAllPipiters();
 
$response = [
    'pipiters' => $pipiters,
];

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
