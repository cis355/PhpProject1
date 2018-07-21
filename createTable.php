<?php
/*
 * File:   createTable.php
 * Author: George Corser (gpcorser@svsu.edu)
 * 
 * Description: Creates a table in a MySQL database
 */

include "database.php";

$sql = "CREATE TABLE  `customers` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    `name` VARCHAR( 100 ) NOT NULL ,
    `email` VARCHAR( 100 ) NOT NULL ,
    `mobile` VARCHAR( 100 ) NOT NULL
    ) ENGINE = INNODB;";

$pdo = Database::connect();

// Set error reporting attributes
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Prepare query for data binding (question marks: ?, ?, ?)
$q = $pdo->prepare($sql);

// Create  table ONLY if table does not already exist
if(!Database::tableExists($pdo, "customers")) {
    $q->execute(array()); // create the table
}
else {
    echo "Table already exists.";
}

Database::disconnect();
?>

