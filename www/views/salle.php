

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="./../public/js/alertify.min.js"></script>
    <link rel="stylesheet" href="./../public/style/alertify.min.css" />
    <!-- include a theme -->
     <link rel="stylesheet" href="./../public/style/themes/default.min.css" />
     <link rel="stylesheet" href="./../public/style/nav.css">

    <link rel="stylesheet" href="./../public/style/bulma.css">
    <link rel="stylesheet" href="./../public/style/app.css">
    <script src="./../public/js/jquery.js"></script>
    <script src="./../public/js/swtt.js"></script>

    <script src="./../public/js/jqurymodal.js"></script>
    <link rel="stylesheet" href="./../public/style/jqurysmodal.css" />



    <?php

require_once "./../controller/SalleController.php";
require_once "./../controller/PipiterController.php";

// SQLite database file path
$database_file = './../db/db.db';

// Instantiate SalleController
$salleController = new SalleController($database_file);
$pipiterController = new PipiterController($database_file);
$pipiters = $pipiterController->getAllPipiters();

// Example usage of the functions

// If form submitted for creating a new salle
if (isset($_POST['create_salle'])) {
    $Nom = $_POST['Nom'];
    $pipiterid = $_POST['pipiterid'];
    $result = $salleController->createSalle($Nom, $pipiterid);
    echo "<p>$result</p>";
    echo  "<script>alertify.alert('Success', '$result')</script>";
}


// If form submitted for updating a salle
if (isset($_POST['update_salle'])) {
    $id = $_POST['id'];
    $Nom = $_POST['Nom'];
    $pipiterid = $_POST['pipiterid'];
    $result = $salleController->updateSalle($id, $Nom, $pipiterid);
    echo "<p>$result</p>";
    echo  "<script>alertify.alert('Success', '$result')</script>";
}

// If form submitted for deleting a salle
if (isset($_POST['delete_salle'])) {
    $id = $_POST['id'];
    $result = $salleController->deleteSalle($id);
    echo "<p>$result</p>";
    echo  "<script>alertify.alert('Success', '$result')</script>";
}

// Get all salles
$salles = $salleController->getAllSalles();

?>
    <title>Salle Management</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        .nav-items li a {display: flex; align-items: center; gap: 5px;}
        li a img {
            width: 30px;
        }
        li a:hover {
            filter: brightness(1.5);
        }
    </style>
</head>
<body>

<nav>
         <div class="menu-icon">
            <span class="fas fa-bars"></span>
         </div>
         <div class="logo">
         <p><a href="#showuSallesermodal" class="button is-info" rel="modal:open">Create Salle</a></p>
         </div>
         <div class="nav-items">
         <li><a href="./../index.php"><img src="./../public/asset/ico/home.png" alt="" srcset=""><span>Home</span></a></li>
            <li><a href="./packupmanager.php"><img src="./../public/asset/ico/exportimport.png" alt="" srcset=""><span>ImportExport</span></a></li>
            <li><a href="./pipiter.php"><img src="./../public/asset/ico/pipiter.png" alt="" srcset=""><span>Pupiter</span></a></li>
            <li><a href="./salle.php"><img src="./../public/asset/ico/salle.png" alt="" srcset=""><span>Salles</span></a></li>
                    </div>
         <div class="search-icon">
            <span class="fas fa-search"></span>
         </div>
         <div class="cancel-icon">
            <span class="fas fa-times"></span>
         </div>
          
         <form method="get" action="./../index.php"> 
            <input  type="text" id="search" name="search" class="search-data" placeholder="Search" >
              <button class="fas fa-search" type="submit" name="search_user">Search</button>
</form>
      </nav>

<!-- Modal for creating a new salle -->
<div id="showuSallesermodal" class="modal">
    <div ></div>
    <div class="modal-content">
        <form method="post">
            <div class="field">
                <label class="label" for="Nom">Nom:</label>
                <div class="control">
                    <input class="input" type="text" id="Nom" name="Nom" required>
                </div>
            </div>
           


                <div class="field">
        <label class="label" for="Pupitre">Pupitre:</label>
        <div class="control">
        <div class="select">
            <select  id="pipiterid" name="pipiterid">
                <option value="noselected">select</option>
                <?php
                foreach ($pipiters as $pipiter) :  ?>
                    <option value="<?php echo $pipiter['ID']; ?>"><?php echo $pipiter['Nom']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>     
       </div>
    </div>

            <div class="field is-grouped">
                <div class="control">
                    <button type="submit" name="create_salle" class="button is-primary">Create Salle</button>
                </div>
                <div class="control">
                    <button class="button is-danger" onclick="closeModal()">Cancel</button>
                </div>
            </div>
        </form>
    </div>
    <button class="modal-close is-large" aria-label="close" onclick="closeModal()"></button>
</div>

<!-- All Salles table -->
<h2>All Salles <?php echo count( $salles) ?></h2>
<table class="table is-fullwidth container" >
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Pipiter ID</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($salles as $salle): ?>
        <tr>
            <td><?php echo $salle['ID']; ?></td>
            <td><?php echo $salle['Nom']; ?></td>
            <td><?php echo $salle['pipiterid']; ?></td>
            <td>
                <form method="post">
                    <input type="hidden" name="id" value="<?php echo $salle['ID']; ?>">
                    <div class="field is-grouped">
                        <div class="control">
                            <input class="input" type="text" name="Nom" value="<?php echo $salle['Nom']; ?>">
                        </div>
                        



                        <div class="field">
        <div class="control">
        <div class="select">
            <select  id="pipiterid" name="pipiterid">
                <option value="noselected">select</option>
                <?php
                foreach ($pipiters as $pipiter) :  ?>
                    <option  <?php if($pipiter['ID']==$salle['pipiterid']){echo "selected" ;} ?> value="<?php echo $pipiter['ID']; ?>"><?php echo $pipiter['Nom']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>     
       </div>
    </div>


                        <div class="control">
                            <button type="submit" name="update_salle" class="button is-primary">Update</button>
                        </div>
                        <div class="control">
                            <button type="submit" name="delete_salle" class="button is-danger">Delete</button>
                        </div>
                    </div>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>


</body>
</html>