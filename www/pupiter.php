<?php
// Include the necessary controller files and initialize the database connection
require_once "./controller/PipiterController.php";
require_once "./controller/SalleController.php";
require_once "./controller/userController.php";
$database_file = './db/db.db';
$userController = new UserController($database_file);

// Pagination variables
$limit = 10; // Update the limit to match your requirements
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Get all pipiters
$pipiterController = new PipiterController($database_file);
$pipiters = $pipiterController->getAllPipiters();

// Check if the search form is submitted
if (isset($_GET['search_user'])) {
    // Retrieve search criteria from the form
    $search_text = isset($_GET['search_text']) ? $_GET['search_text'] : '';
    $search_date = isset($_GET['search_date']) ? $_GET['search_date'] : '';
    $search_time = isset($_GET['search_time']) ? $_GET['search_time'] : '';
    $pipiter_id = isset($_GET['pipiter_id']) ? $_GET['pipiter_id'] : 'noselected';

    // Get users based on search criteria
    $users = $userController->getAllUsersPaginationWithsearch($limit, $offset, $search_text, $search_date, $search_time, $pipiter_id);

    // Count total users matching the search criteria
    $total_users = $userController->countUsersWithSearch($search_text, $search_date, $search_time, $pipiter_id);
} else {
    // If search form is not submitted, get users for the current page without search criteria
    $users = $userController->getAllUsersPagination($limit, $offset);
    // Get total number of users
    $total_users  =count( $userController->getAllUsers());

}

// Calculate total pages for pagination
$total_pages = ceil($total_users / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="./public/js/alertify.min.js"></script>
    <link rel="stylesheet" href="./public/style/alertify.min.css" />
    <!-- include a theme -->
     <link rel="stylesheet" href="./public/style/themes/default.min.css" />
     <link rel="stylesheet" href="./public/style/nav.css">

    <link rel="stylesheet" href="./public/style/bulma.css">
    <link rel="stylesheet" href="./public/style/app.css">
    <script src="./public/js/jquery.js"></script>
    <script src="./public/js/swtt.js"></script>

    <script src="./public/js/jqurymodal.js"></script>
    <link rel="stylesheet" href="./public/style/jqurysmodal.css" />
    <title>User Management</title>
    <!-- Include your CSS and JavaScript files -->
</head>
<body>
    <style>
        .field-body{
         flex-direction: column;
        }
    </style>

<button onclick="openSearch()">Open Search</button>
<div class="container searchdiv" >
        <!-- Search Form -->
        <form action="pupiter.php" method="get">
            <input type="text" name="search_text" placeholder="Enter text...">
            <input type="date" name="search_date" placeholder="Enter date...">
            <input type="time" name="search_time" placeholder="Enter time...">
            <select name="pipiter_id">
                <option value="noselected">Select Pipiter</option>
                <?php foreach ($pipiters as $pipiter) : ?>
                    <option value="<?php echo $pipiter['ID']; ?>"><?php echo $pipiter['Nom']; ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="search_user">Search</button>
        </form>
    </div>

     <!-- User Table -->
     <h2 class="user-count container">All Users <?php echo $total_users; ?></h2>
     <div class="container">
     <table class="table is-fullwidth is-bordered is-striped is-hoverable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Titre</th>
            <th>Date Conferences</th>
            <th>Horaire</th>
            <th>Duree Com</th>
            <th>Nom Salle</th>
            <th>Pipiter ID</th>
            <th>Open File</th>
            <!-- Add additional table headers if needed -->
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user) : ?>
            <tr <?php if ($user['Etat'] == "oui") echo "class='opend'"; ?>>
                <td><?php echo $user['ID']; ?></td>
                <td><?php echo $user['Nom']; ?></td>
                <td><?php echo $user['Titre']; ?></td>
                <td><?php echo $user['Date_conferences']; ?></td>
                <td><?php echo $user['Horaire']; ?></td>
                <td><?php echo $user['Duree_com']; ?></td>
                <td><?php echo $user['Nom_salle']; ?></td>
                <td><?php echo $user['pipiterid']; ?></td>
                <td><a href="./uploads/presentation/<?php echo $user['Fichier']; ?>" download>Try</a></td>
                <td><a href="https://docs.google.com/viewer?url=<?php echo urlencode("http://example.com/uploads/presentation/{$user['Fichier']}"); ?>&embedded=true" target="_blank">View Presentation</a></td>

            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</div>

<!-- Pagination -->
<div class="pagination" style="display: flex; justify-content: center; gap: 20px;">
    <?php
    // Construct the base URL without the page number
    $baseUrl = strtok($_SERVER["REQUEST_URI"], '?');

    // Construct the search parameters string
    $searchParams = isset($_GET['search_user']) ? '&search_text=' . $_GET['search_text'] . '&search_date=' . $_GET['search_date'] . '&search_time=' . $_GET['search_time'] . '&search_user=&pipiter_id=' . $_GET['pipiter_id'] : '';

    // Loop through total pages to create pagination links
    for ($i = 1; $i <= $total_pages; $i++) {
        // Add the page number to the base URL
        $url = $baseUrl . '?page=' . $i . $searchParams;
        ?>
        <a href="<?php echo $url; ?>" <?php if ($i == $page) echo "class='active'"; ?>><?php echo $i; ?></a>
    <?php } ?>
</div>


</body>

<script>
    // Function to open the search form using SweetAlert
    function openSearch() {
        const msg = `
            <div class="container searchdiv">
                <!-- Search Form -->
                <form action="pupiter.php" method="get">
                    <div class="field is-horizontal">
                        <div class="field-body">
                            <div class="field">
                                <label class="label">Text:</label>
                                <div class="control">
                                    <input class="input" type="text" name="search_text" placeholder="Enter text...">
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Date:</label>
                                <div class="control">
                                    <input class="input" type="date" name="search_date" placeholder="Enter date...">
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Time:</label>
                                <div class="control">
                                    <input class="input" type="time" name="search_time" placeholder="Enter time...">
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Pipiter:</label>
                                <div class="control">
                                    <div class="select">
                                        <select name="pipiter_id">
                                            <option value="noselected">Select Pipiter</option>
                                            <?php foreach ($pipiters as $pipiter) : ?>
                                                <option value="<?php echo $pipiter['ID']; ?>"><?php echo $pipiter['Nom']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="field">
                                <div class="control">
                                    <button class="button is-primary" type="submit" name="search_user">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        `;
        alertify.alert("Search", msg);
    }
</script>



</html>
