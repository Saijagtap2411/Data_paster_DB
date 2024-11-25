<?php
require 'vendor/autoload.php';  

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "task2";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$inputFileName = 'Uploading.xlsx';  
$spreadsheet = IOFactory::load($inputFileName);
$sheet = $spreadsheet->getActiveSheet();

foreach ($sheet->getRowIterator(2) as $row) {
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(FALSE);

    $data = [];
    foreach ($cellIterator as $cell) {
        $data[] = $cell->getValue();  
    }

    
    $sql = "INSERT INTO users (sr_no, name, middle_name, last_name, email, phone, city, state, country, blood_group) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

   
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("isssssssss", $data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], $data[8], $data[9]); // 'i' for integer, 's' for string
        $stmt->execute();
    } else {
        echo "Error: " . $conn->error;
    }
}
echo "Data uploaded successfully!!!!!";
?>
