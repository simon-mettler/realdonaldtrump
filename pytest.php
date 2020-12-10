<?php 

include_once('inc/config.php');
include_once('inc/templates/functions.php');
include_once('inc/templates/parts.php');

$db = new SQLite3(DIR . '/db/realdonaldtrump.db');

print_head('PyTemplate');


// Get data from database.
$dbQuery = $db->prepare("SELECT content FROM realdonaldtrump LIMIT 10");
$dbData = $dbQuery->execute();

// Concat to string.
while( $row = $dbData->fetchArray(SQLITE3_ASSOC) ) { 
	$dataStr .= $row['content'].' ';
}

// Send string to python script and get result.
$pyResult = shell_exec('python3 pytest.py ' . escapeshellarg($dataStr) . " 2>&1");

// Make something with the result.
echo 'A total of ' . $pyResult . ' Words...';

print_foot();

