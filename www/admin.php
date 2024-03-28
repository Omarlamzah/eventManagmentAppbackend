

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
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
  
<?php
require_once "./controller/PipiterController.php";
require_once "./controller/SalleController.php";
require_once "./controller/userController.php";
// SQLite database file path
$database_file = './db/db.db';

// Instantiate controllers
$userController = new UserController($database_file);
$pipiterController = new PipiterController($database_file);
$salleController = new SalleController($database_file);

// Get all pipiters and salles
$pipiters = $pipiterController->getAllPipiters();
$salles = $salleController->getAllSalles();

// If form submitted for creating a new user
if (isset($_POST['create_user'])) {
    // Retrieve form data
    $Nom = $_POST['Nom'];
    $Titre = $_POST['Titre'];
    $Date_conferences = $_POST['Date_conferences'];
    $Horaire = $_POST['Horaire'];
    $Duree_com = $_POST['Duree_com'];
    $Nom_salle = $_POST['Nom_salle'];
    $Etat = $_POST['Etat'];
    $Fichier = $_POST['Fichier'];
    $pipiterid = $_POST['pipiterid'];
    
    // Create user
    $result = $userController->createUser($Nom, $Titre, $Date_conferences, $Horaire, $Duree_com, $Nom_salle, $Etat, $Fichier, $pipiterid);
    echo "<p>$result</p>";
    echo  "<script>alertify.alert('Success', '$result')</script>";}

// If form submitted for updating a user
if (isset($_POST['update_user'])) {
    // Retrieve form data
    $id = $_POST['id'];
    $Nom = $_POST['Nom'];
    $Titre = $_POST['Titre'];
    $Date_conferences = $_POST['Date_conferences'];
    $Horaire = $_POST['Horaire'];
    $Duree_com = $_POST['Duree_com'];
    $Nom_salle = $_POST['Nom_salle'];
    $Etat = $_POST['Etat'];
    $Fichier = $_POST['Fichier'];
    $pipiterid = $_POST['pipiterid'];
    
    // Update user
    $result = $userController->updateUser($id, $Nom, $Titre, $Date_conferences, $Horaire, $Duree_com, $Nom_salle, $Etat, $Fichier, $pipiterid);
    echo "<p>$result</p>";
    echo  "<script>alertify.alert('Success', '$result')</script>";}

// If form submitted for deleting a user
if (isset($_POST['delete_user'])) {
    // Retrieve user ID
    $id = $_POST['id'];
    
    // Delete user
    $result = $userController->deleteUser($id);
    echo "<p>$result</p>";
    echo  "<script>alertify.alert('Success', '$result')</script>";}
// Pagination variables
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

// Get total number of users
$total_users = 0;

// If search button clicked
if (isset($_GET['search_user'])) {
    // Retrieve search query
    $query = $_GET['search'];
    
    // Get users based on search query
    $users = $userController->getAllUsersPagination($limit, $offset, $query);
    
    // Update total users based on search result
    $total_users = count($users);
} else {
    // Get users for the current page
    $users = $userController->getAllUsersPagination($limit, $offset);
    
    // Get total number of users
    $total_users = count($userController->getAllUsers());
}

// Calculate total pages
$total_pages = ceil($total_users / $limit);
?>

</head>

<body>
    <style>
          .nav-items li a {display: flex; align-items: center; gap: 5px;}
        li a img {
            width: 30px;
        }
        li a:hover {
            filter: brightness(1.5);
        }











        .user-count {
    font-size: 1.5rem;
    margin-bottom: 20px;
}

.user-table {
    width: 100%;
    border-collapse: collapse;
}

.user-table th, .user-table td {
    border: 1px solid #ddd;
    padding: 8px;
}

.user-table th {
    background-color: #f2f2f2;
}

.salle-wrapper {
    display: flex;
    gap: 4px;
    padding: 10px;
}

.verify-btn, .join-btn, .update-btn {
    cursor: pointer;
    padding: 5px 10px;
    border: none;
    border-radius: 4px;
    background-color: #007bff;
    color: #fff;
    text-decoration: none;
    text-align: center;
    transition: background-color 0.3s;
}

.verify-btn:hover, .join-btn:hover, .update-btn:hover {
    background-color: #0056b3;
}

    </style>
<nav>
         <div class="menu-icon">
            <span class="fas fa-bars"></span>
         </div>
         <div class="logo">
         <p><a href="#createpackmodal" class="button is-primary" rel="modal:open">Add user</a></p>

         </div>
         <div class="nav-items">
         <li><a href="./index.php"><img src="./public/asset/ico/home.png" alt="" srcset=""><span>Home</span></a></li>
            <li><a href="./views/packupmanager.php"><img src="./public/asset/ico/exportimport.png" alt="" srcset=""><span>ImportExport</span></a></li>
            <li><a href="./views/pipiter.php"><img src="./public/asset/ico/pipiter.png" alt="" srcset=""><span>Pupiter</span></a></li>
            <li><a href="./views/salle.php"><img src="./public/asset/ico/salle.png" alt="" srcset=""><span>Salles</span></a></li>
                    </div>
         <div class="search-icon">
            <span class="fas fa-search"></span>
         </div>
         <div class="cancel-icon">
            <span class="fas fa-times"></span>
         </div>
          
         <form method="get" action="./admin.php"> 
            <input  type="text" id="search" name="search" class="search-data" placeholder="Search" >
              <button class="fas fa-search" type="submit" name="search_user">Search</button>
</form>
      </nav>
      


    

    <div id="createpackmodal" class="modal">
    <h2 class="title is-2">Create User</h2>
<form method="post">
    <div class="field">
        <label class="label" for="Nom">Nom:</label>
        <div class="control">
            <input class="input" type="text" id="Nom" name="Nom" required>
        </div>
    </div>
    <div class="field">
        <label class="label" for="Titre">Titre:</label>
        <div class="control">
            <input class="input" type="text" id="Titre" name="Titre" required>
        </div>
    </div>
    <div class="field">
        <label class="label" for="Date_conferences">Date conferences:</label>
        <div class="control">
            <input class="input" type="date" id="Date_conferences" name="Date_conferences" required>
        </div>
    </div>
    <div class="field">
        <label class="label" for="Horaire">Horaire:</label>
        <div class="control">
            <input class="input" type="time" id="Horaire" name="Horaire" required>
        </div>
    </div>
    <div class="field">
        <label class="label" for="Duree_com">Duree com:</label>
        <div class="control">
            <input class="input" type="text" id="Duree_com" name="Duree_com" required>
        </div>
    </div>
    <div class="field">
        <label class="label" for="Nom_salle">Nom salle:</label>
        <div class="select">
                                 <select name="Nom_salle" id="">

                                    <option value="noselected">---selecte---</option>
                                    <?php
                                    foreach ($salles as $salle) :  ?>
                                        <option value="<?php echo $salle['ID']; ?>"><?php echo $salle['Nom']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
    </div>
    <div class="field">
        <label class="label" for="Pupitre">Pupitre:</label>
        <div class="control">
        <div class="select">
            <select name="pipiterid" id="">
                <option value="noselected">select</option>
                <?php
                $database_file = './db/db.db';
                // Instantiate PipiterController
                $pipiterController = new PipiterController($database_file);
                // Get all pipiters
                $pipiters = $pipiterController->getAllPipiters();

                foreach ($pipiters as $pipiter) :  ?>
                    <option value="<?php echo $pipiter['ID']; ?>"><?php echo $pipiter['Nom']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>     
       </div>
    </div>
    <div class="field">
        <label class="label" for="Etat">Etat:</label>
        <div class="select">
                                 <select name="Etat" id="">
                                    <option value="nooption">---selecte---</option>
                                    <option value="oui" >opened</option>
                                    <option value="no">not opened</option>
                                </select>
                              </div>
    </div>
    <div class="field">
        <label class="label" for="Fichier">Fichier:</label>
        <div class="control">
            <input class="input" type="text" id="Fichier" name="Fichier" >
        </div>
    </div>
     
    <div class="field">
        <div class="control">
            <button class="button is-primary" type="submit" name="create_user">Create User</button>
        </div>
    </div>
</form>


    </div>


    <h2 class="user-count container">All Users <?php echo count($users); ?></h2>
        <table class="user-table container">
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Titre</th>
            <th>Date Conferences</th>
            <th>Horaire</th>
            <th>Duree Com</th>
            <th>Nom Salle</th>
          
        </tr>
        <?php foreach ($users as $user) : ?>
            <tr>
                <td><?php echo $user['ID']; ?></td>
                <td><?php echo $user['Nom']; ?></td>
                <td><?php echo $user['Titre']; ?></td>
                <td><?php echo $user['Date_conferences']; ?></td>
                <td><?php echo $user['Horaire']; ?></td>
                <td><?php echo $user['Duree_com']; ?></td>
            
                <td style="display: flex; gap:4px; padding:10px;">
                     <!--vérifier modal -->
                     <button class="verify-btn" onclick="CustomAlert('file status', 'fiche est : ' + '<?php echo $user['Fichier']; ?>')">vérifier</button>                
                    <!--Joindre modal -->
                   <!--joinder  with alertify -->
                   <button onclick="CustomAlert('choose files', `<form onsubmit='uploadFile(event)' action='' method='post' enctype='multipart/form-data'>
    <div class='field'>
        <label class='label' for='fileupload'>Joinder file <?php echo $user['Nom']?></label>
        <div class='control'>
            <input value='<?php echo $user['ID']?>' type='hidden' name='IDuser'>
            <label for='fileupload' class='file-label'>File presentation</label>
            <div class='file has-name'>
                <label class='file-label'>
                    <input onchange='displayFileName(event, &quot;fileuploadFileName&quot;)' required class='file-input' type='file' name='fileupload' id='fileupload'>
                    <span class='file-cta'>
                        <span class='file-icon'>
                            <i class='fas fa-upload'></i>
                        </span>
                        <span class='file-label'>
                            Choose a file…
                        </span>
                    </span>
                </label>
            </div>
            <span id='fileuploadFileName'></span>
            
            <label for='fileuploadprofile' class='file-label'>File profile</label>
            <div class='file has-name'>
                <label class='file-label'>
                    <input onchange='displayFileName(event, &quot;fileuploadprofileFileName&quot;)' class='file-input' type='file' name='fileuploadprofile' id='fileuploadprofile'>
                    <span class='file-cta'>
                        <span class='file-icon'>
                            <i class='fas fa-upload'></i>
                        </span>
                        <span class='file-label'>
                            Choose a file…
                        </span>
                    </span>
                </label>
            </div>
            <span id='fileuploadprofileFileName'></span>
        </div>
    </div>
    <div class='field'>
        <div class='control'>
            <button type='submit' class='button is-success'>Upload</button>
        </div>
    </div>
</form>`);">joinder 2</button>

                   <!--joinder  with alertify -->


                     </div>
                    <!--update modal -->
                    <p><a href="#showusermodal<?php echo  $user['ID']?>" class="button is-info" rel="modal:open">Modife</a></p>
                    <div id="showusermodal<?php echo  $user['ID']?>" class="modal">

                        <form method="post">
                            <input type="hidden" name="id" value="<?php echo $user['ID']; ?>">
                            <div class="field">
                                <label class="label">Nom</label>
                                <div class="control">
                                    <input class="input" type="text" name="Nom" value="<?php echo $user['Nom']; ?>">
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Titre</label>
                                <div class="control">
                                    <input class="input" type="text" name="Titre" value="<?php echo $user['Titre']; ?>">
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Date_conferences</label>
                                <div class="control">
                                    <input class="input" type="date" name="Date_conferences" value="<?php echo $user['Date_conferences']; ?>">
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Horaire</label>
                                <div class="control">
                                    <input class="input" type="time" name="Horaire" value="<?php echo $user['Horaire']; ?>">
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Duree_com</label>
                                <div class="control">
                                    <input class="input" type="number" name="Duree_com" value="<?php echo $user['Duree_com']; ?>">
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Nom_salle  : <?php echo $user['Nom_salle']; ?></label>
                                <div class="select">
                                 <select name="Nom_salle" id="">

                                    <option value="noselected">---selecte---</option>
                                    <?php
                                    foreach ($salles as $salle) :  ?>
                                        <option value="<?php echo $salle['ID']; ?>" <?php if ($salle['Nom'] == $user['Nom_salle']) echo 'selected'; ?>><?php echo $salle['Nom']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                                 
                            </div>
                            <div class="field">
                                <label class="label">Pupitre</label>
                                
                                <div class="select">
                                   <select name="pipiterid" id="">
                                    <option value=""></option>
                                    <?php
                                    foreach ($pipiters as $pipiter) :  ?>
                                        <option value="<?php echo $pipiter['ID']; ?>" <?php if ($user['pipiterid'] == $pipiter['ID']) echo 'selected'; ?>><?php echo $pipiter['Nom']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            </div>
                            <div class="field">
                                <label class="label">Etat</label>
                                <div class="control">
                                 <div class="select">
                                 <select name="Etat" id="">
                                    <option value="nooption">---selecte---</option>
                                    <option value="oui" <?php if ($user['Etat'] == 'oui') echo 'selected'; ?>>opened</option>
                                    <option value="no" <?php if ($user['Etat'] == 'no') echo 'selected'; ?>>not opened</option>
                                </select>
                              </div>

                            <div class="field">
                                <label class="label">Fichier</label>
                                <div class="control">
                                    <input class="input" type="text" name="Fichier" value="<?php echo $user['Fichier']; ?>">
                                </div>
                            </div>
                            
                            <div class="field is-grouped">
                                <div class="control">
                                    <button class="button is-primary" type="submit" name="update_user">Update</button>
                                </div>
                                <div class="control">
                                    <button class="button is-danger" type="submit" name="delete_user">Delete</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>


    <!-- Pagination -->
    <div style="display: flex;  justify-content: center; gap: 20px;" class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
            <a href="?page=<?php echo $i; ?>" <?php if ($i == $page) echo "class='active'"; ?>><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>

</body>
<script>
 function uploadFile(event) {
    event.preventDefault(); // Prevent the default form submission

    // Get form data
    const form = event.target;
    const formData = new FormData(form);

    // Fetch options
    const options = {
        method: 'POST',
        body: formData
    };

    // Fetch request
    fetch('./php/user/crud.php', options)
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to upload file');
            }
            return response.text(); // Assuming server returns text response
        })
        .then(data => {
            // Handle successful upload
            alertify.alert("File uploaded successfully",data);
            console.log('File uploaded successfully:', data);
        })
        .catch(error => {
            // Handle errors
            console.error('Error uploading file:', error.message);
        });
}
 
</script>

<script>
    function CustomAlert(title,msg){
        alertify.alert(title,msg);
    }
</script>
<script>
         const menuBtn = document.querySelector(".menu-icon span");
         const searchBtn = document.querySelector(".search-icon");
         const cancelBtn = document.querySelector(".cancel-icon");
         const items = document.querySelector(".nav-items");
         const form = document.querySelector("form");
         menuBtn.onclick = ()=>{
           items.classList.add("active");
           menuBtn.classList.add("hide");
           searchBtn.classList.add("hide");
           cancelBtn.classList.add("show");
         }
         cancelBtn.onclick = ()=>{
           items.classList.remove("active");
           menuBtn.classList.remove("hide");
           searchBtn.classList.remove("hide");
           cancelBtn.classList.remove("show");
           form.classList.remove("active");
           cancelBtn.style.color = "#ff3d00";
         }
         searchBtn.onclick = ()=>{
           form.classList.add("active");
           searchBtn.classList.add("hide");
           cancelBtn.classList.add("show");
         }
      </script>

<script>
    function displayFileName(event, targetId) {
        const input = event.target;
        const fileName = input.files[0].name;
        document.getElementById(targetId).textContent = fileName;
    }
</script>


</html>