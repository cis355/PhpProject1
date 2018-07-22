<?php

require "../database/database.php";
require "customer.class.php";
$cust = new Customer();

if(isset($_POST["name"])) $cust->name = $_POST["name"];
if(isset($_POST["email"])) $cust->email = $_POST["email"];
if(isset($_POST["mobile"])) $cust->mobile = $_POST["mobile"];

if(isset($_GET["id"])) $id = $_GET["id"];
else $id = 0;

// "fun" (function): 0=list, 1=create, 2=read, 3=update, 4=delete
if(isset($_GET["fun"])) $fun = $_GET["fun"];
else $fun = 0;

switch ($fun) {
    case 1: // create
        $cust->create_record(); // display "create" form
        break;
    case 2: // read
        if($id > 0) $cust->read_record($id); // display "read" form
        else $cust->list_records(); // if no id, go to list screen
        break;
    case 3: // update
        if($id > 0) $cust->update_record($id); // display "update" form
        else $cust->list_records(); // if no id, go to list screen
        break;
    case 4: // delete
        if($id > 0) $cust->delete_record($id); // display "delete" form
        else $cust->list_records(); // if no id, go to list screen
        break;
    case 11: // insert database record from create_record() form
        $cust->insert_db_record(); // insert a new db record
        break;
    case 33: // update database record from update_record() form
        $cust->update_db_record($id);
        break;
    case 44: // delete database record from delete_record() form
        $cust->delete_db_record($id);
        break;
    case 0: // list
    default: // list
        $cust->list_records();
}