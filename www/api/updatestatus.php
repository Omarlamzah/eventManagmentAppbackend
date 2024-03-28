<?php
require_once "./../controller/userController.php";

// Instantiate UserController
$database_file = './../db/db.db';
$userController = new UserController($database_file);

// Read and decode the JSON payload
$data = json_decode(file_get_contents('php://input'), true);

// Check if both status and id data were received via POST request
if (isset($data['status']) && isset($data['id'])) {
    // Get the status and id data from the POST request
    $status = $data['status'];
    $id = $data['id'];

    // Update the user status using the UserController
    $result = $userController->updateUserstatus($id, $status);

    // Send back a response indicating the success or failure of the operation
    if ($result) {
        // Status updated successfully
        http_response_code(200);
        echo json_encode(array("message" => $result));
      //  echo json_encode(array("message" => "Status updated successfully"));
    } else {
        // Failed to update status
        http_response_code(500);
        echo json_encode(array("message" => "Failed to update status"));
    }
} else {
    // Status data not received
    http_response_code(400);
    echo json_encode(array("message" => "Status data not received"));
}
?>
