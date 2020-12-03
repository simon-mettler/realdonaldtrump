<?php

include_once('inc/config.php');
include_once('inc/templates/functions.php');
include_once('inc/templates/parts.php');

$db = new SQLite3(DIR . '/db/realdonaldtrump.db');

print_head('Testtitel');

echo "<form action='search.php' method='post'>";
textInput('suche');
dateInput('start', 'Von:');
echo "<input name='like' type='number' value='42'>";
echo "<input type='submit'></form>";
	

$alltweets = $db -> query("SELECT content, date, retweets, favorites FROM realdonaldtrump LIMIT 100"); 
  
while( $row = $alltweets->fetchArray(SQLITE3_BOTH) ) { 
	theTweet($row);
}

print_foot();
