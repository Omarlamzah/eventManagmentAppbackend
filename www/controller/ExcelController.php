<?php
require '../vendor/autoload.php'; // Include Composer's autoloader
 

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelController
{
    // Export data to Excel
  // Export data to Excel
public function exportToExcel()
{
    // Create SQLite database connection
    $db = new SQLite3('./../db/db.db');

    $spreadsheet = new Spreadsheet();
    $activeWorksheet = $spreadsheet->getActiveSheet();

    $activeWorksheet->setCellValue('A1', 'ID');
    $activeWorksheet->setCellValue('B1', 'Nom');
    $activeWorksheet->setCellValue('C1', 'Titre');
    $activeWorksheet->setCellValue('D1', 'Date_conferences');
    $activeWorksheet->setCellValue('E1', 'Horaire');
    $activeWorksheet->setCellValue('F1', 'Duree_com');
    $activeWorksheet->setCellValue('G1', 'Nom_salle');
    $activeWorksheet->setCellValue('H1', 'Pupitre');
    $activeWorksheet->setCellValue('I1', 'Etat');
    $activeWorksheet->setCellValue('J1', 'Fichier');
    $activeWorksheet->setCellValue('K1', 'pipiterid');

    // Fetch all data from the user table
    $result = $db->query("SELECT * FROM user ORDER BY ID DESC");

    // Add data from the database to the spreadsheet
    $row = 2;
    while ($row_data = $result->fetchArray(SQLITE3_ASSOC)) {
        $activeWorksheet->setCellValue('A' . $row, $row_data['ID']);
        $activeWorksheet->setCellValue('B' . $row, $row_data['Nom']);
        $activeWorksheet->setCellValue('C' . $row, $row_data['Titre']);
        $activeWorksheet->setCellValue('D' . $row, $row_data['Date_conferences']);
        $activeWorksheet->setCellValue('E' . $row, $row_data['Horaire']);
        $activeWorksheet->setCellValue('F' . $row, $row_data['Duree_com']);
        $activeWorksheet->setCellValue('G' . $row, $row_data['Nom_salle']);
        $activeWorksheet->setCellValue('H' . $row, $row_data['Pupitre']);
        $activeWorksheet->setCellValue('I' . $row, $row_data['Etat']);
        $activeWorksheet->setCellValue('J' . $row, $row_data['Fichier']);
        $activeWorksheet->setCellValue('K' . $row, $row_data['pipiterid']);
        $row++;
    }

    // Get the current date
    $currentDateTime = date('Y-m-d_H-i-s');

    // Save the Excel file with the current date in the filename
    $filename = './../uploads/packup/insct_' . $currentDateTime . '.xlsx';
    $writer = new Xlsx($spreadsheet);
    $writer->save($filename);

    return $filename;
}


    // Helper function to convert row number to Excel column letter
    private function getColumnLetter($columnNumber)
    {
        return chr($columnNumber + 64);
    }

    public function importFromExcel($file)
    {
        // Create SQLite database connection
        $db = new SQLite3('./../db/db.db');
    
        // Load the Excel file
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
    
        // Assume the first row contains headers, so we start from the second row
        $row = 2;
        while ($sheet->getCell('A' . $row)->getValue() != "") {
            // Retrieve data from each cell in the row
            // Exclude the 'ID' column since it's auto-increment in SQLite
            $Nom = $sheet->getCell('B' . $row)->getValue();
            $Titre = $sheet->getCell('C' . $row)->getValue();
            $Date_conferences = $sheet->getCell('D' . $row)->getValue();
            $Horaire = $sheet->getCell('E' . $row)->getValue();
            $Duree_com = $sheet->getCell('F' . $row)->getValue();
            $Nom_salle = $sheet->getCell('G' . $row)->getValue();
            $Pupitre = $sheet->getCell('H' . $row)->getValue();
            $Etat = $sheet->getCell('I' . $row)->getValue();
            $Fichier = $sheet->getCell('J' . $row)->getValue();
            $pipiterid = $sheet->getCell('K' . $row)->getValue();
    
            // Insert data into the SQLite table
            $sql = "INSERT INTO user (Nom, Titre, Date_conferences, Horaire, Duree_com, Nom_salle, Pupitre, Etat, Fichier, pipiterid) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(1, $Nom);
            $stmt->bindParam(2, $Titre);
            $stmt->bindParam(3, $Date_conferences);
            $stmt->bindParam(4, $Horaire);
            $stmt->bindParam(5, $Duree_com);
            $stmt->bindParam(6, $Nom_salle);
            $stmt->bindParam(7, $Pupitre);
            $stmt->bindParam(8, $Etat);
            $stmt->bindParam(9, $Fichier);
            $stmt->bindParam(10, $pipiterid);
            $stmt->execute();
    
            $row++;
        }
    
        // Close the database connection
        $db->close();
    }
    
}
