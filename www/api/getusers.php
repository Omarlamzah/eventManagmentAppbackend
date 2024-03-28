<?php
require_once "./../controller/userController.php";

$database_file = './../db/db.db';
$userController = new UserController($database_file);

// Pagination variables
$limit = isset($_GET['limit']) ? $_GET['limit'] : 10; // Limit per page
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page
$offset = ($page - 1) * $limit; // Offset calculation

// Search parameters
$search_text = isset($_GET['search_text']) ? $_GET['search_text'] : '';
$search_date = isset($_GET['search_date']) ? $_GET['search_date'] : '';
$search_time = isset($_GET['search_time']) ? $_GET['search_time'] : '';
$pipiter_id = isset($_GET['pipiter_id']) ? $_GET['pipiter_id'] : 'noselected';

// Get users based on pagination and search criteria
$users = $userController->getAllUsersPaginationWithSearch($limit, $offset, $search_text, $search_date, $search_time, $pipiter_id);
$total_users = $userController->countUsersWithSearch($search_text, $search_date, $search_time, $pipiter_id);

// Prepare response
$response = [
    'users' => $users,
    'total_users' => $total_users,
    'total_pages' => ceil($total_users / $limit)
];

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
