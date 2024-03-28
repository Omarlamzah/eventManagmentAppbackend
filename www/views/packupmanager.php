

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
    <title>File View</title>
</head>

<body>

<style>


.formimport,.sectionexport{
    width: 50%;
    margin: auto;
    display: flex;
    flex-direction: column;
    text-align: center;
    margin-top: 50px;
}
.drop-area {
    border: 2px dashed #ccc;
    padding: 20px;
    text-align: center;
    display: flex;
    justify-content: center;
    flex-direction: column;
    align-items: center;
}

.file-label {
    cursor: pointer;
    font-size: 16px;
}

.file-name {
    margin-top: 10px;
    font-size: 14px;
    color: green;
    background:#fff ;
    max-width: 25em;
}

#importButton {
    display: block;
    margin-top: 20px;
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}

#importButton:hover {
    background-color: #0056b3;
}

.highlight {
    border-color: #007bff;
}


          .nav-items li a {display: flex; align-items: center; gap: 5px;}
        li a img {
            width: 30px;
        }
        li a:hover {
            filter: brightness(1.5);
        }



        
    </style>
<nav>
         <div class="menu-icon">
            <span class="fas fa-bars"></span>
         </div>
        
         <div class="nav-items">
         <li><a href="./../index.php"><img src="../public/asset/ico/home.png" alt="" srcset=""><span>Home</span></a></li>
            <li><a href="./packupmanager.php"><img src="../public/asset/ico/exportimport.png" alt="" srcset=""><span>ImportExport</span></a></li>
            <li><a href="./pipiter.php"><img src="../public/asset/ico/pipiter.png" alt="" srcset=""><span>Pupiter</span></a></li>
            <li><a href="./salle.php"><img src="../public/asset/ico/salle.png" alt="" srcset=""><span>Salles</span></a></li>
                    </div>
         <div class="search-icon">
            <span class="fas fa-search"></span>
         </div>
         <div class="cancel-icon">
            <span class="fas fa-times"></span>
         </div>
          
         <form method="get" action=""> 
            <input  type="text" id="search" name="search" class="search-data" placeholder="Search" >
              <button class="fas fa-search" type="submit" name="search_user">Search</button>
</form>
      </nav>
   
      

<!-- Link to export data to Excel -->

<section class="sectionexport">
<h2 >Export Data to Excel</h2>
<p class="button is-primary"><a href="packupmanager.php?export">Export to Excel</a>
</p>
</section>

    <!-- Form to upload Excel file for import -->
<form class="formimport" id="importForm" action="packupmanager.php?import" method="post" enctype="multipart/form-data">
    <div id="dropArea" class="drop-area">
    <h2>Import Data from Excel</h2>

        <label for="fileInput" class="file-label">Click here to select an Excel file</label>
        <input type="file" name="file" id="fileInput" class="file-input" accept=".xlsx" style="display: none;">
        <div class="file-name" id="fileName"></div>
    </div>
    <button type="button" id="importButton">Import</button>
</form>

<hr>

</body>

<script>
    // Get drop area and file input element
    const dropArea = document.getElementById('dropArea');
    const fileInput = document.getElementById('fileInput');
    const importForm = document.getElementById('importForm');
    const importButton = document.getElementById('importButton');
    const fileNameDisplay = document.getElementById('fileName');

    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    // Highlight drop area when file is dragged over
    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, unhighlight, false);
    });

    // Handle dropped files
    dropArea.addEventListener('drop', handleDrop, false);

    // Handle file selection
    fileInput.addEventListener('change', handleFileSelect, false);

    // Handle import button click
    importButton.addEventListener('click', handleImportClick, false);

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function highlight() {
        dropArea.classList.add('highlight');
    }

    function unhighlight() {
        dropArea.classList.remove('highlight');
    }

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        handleFiles(files);
    }

    function handleFiles(files) {
        const file = files[0];
        fileInput.files = files;
        displayFileName(file.name);
    }

    function handleFileSelect() {
        const file = fileInput.files[0];
        displayFileName(file.name);
    }

    function displayFileName(name) {
        fileNameDisplay.textContent = name;
    }

    function handleImportClick() {
        importForm.submit();
    }




    function CustomAlert(title,msg){
        alertify.alert(title,msg);
    }
</script>
</html>



<?php
// Include the ExcelController class
include '../controller/ExcelController.php';

// Create an instance of the ExcelController
$excelController = new ExcelController();

// Check if the exportToExcel function should be executed
if (isset($_GET['export'])) {
    $filename = $excelController->exportToExcel();
    // Construct the download link
    $downloadLink = '<a href="' . $filename . '">Download Excel File</a>';
    // Escape the double quotes within the anchor tag
    $escapedDownloadLink = str_replace('"', '\"', $downloadLink);
    // Display a JavaScript alert with the filename and a clickable link
    echo '<script>';
    echo 'CustomAlert("File Download", "' . $escapedDownloadLink . '");';
    echo '</script>';
}

// Check if the importFromExcel function should be executed
if (isset($_GET['import']) && isset($_FILES['file'])) {
    // Import data from the uploaded Excel file
    $excelController->importFromExcel($_FILES['file']['tmp_name']);
    // Display a JavaScript alert indicating successful import
    echo '<script>';
    echo 'CustomAlert("Data Imported", "Data imported successfully.");';
    echo '</script>';
}
?>
