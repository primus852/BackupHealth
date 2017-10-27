<?php

require_once 'vendor/autoload.php';

use primus852\Database;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


/* Projects Connection */
$connection = new Database(true);

/* All Projects that are active */
$projects = $connection->list_projects();

print_r($projects);
die;

/* Close DB Connection */
$connection->close_connection();