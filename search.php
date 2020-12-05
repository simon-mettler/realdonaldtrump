<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once('inc/config.php');
include_once('inc/templates/functions.php');
include_once('inc/templates/parts.php');

$db = new SQLite3(DIR . '/db/realdonaldtrump.db');

print_head('Search');


// Search string: case insensitive.
if (empty($_GET['searchWord'])) {
	$search = '%';
} else {
	$search = '%'.$_GET['searchWord'].'%';
}
$searchWord = $_GET['searchWord'] ?? '';

$limit = $_GET['limit'] ?? '100';
$favMin = $_GET['favMin'] ?? '0';
$favMax = $_GET['favMax'] ?? '835575';
$retMin = $_GET['retMin'] ?? '0';
$retMax = $_GET['retMax'] ?? '302269';
$dateMin = $_GET['dateMin'] ?? '2009-05-04';
$dateMax = $_GET['dateMax'] ?? '2020-06-17';

// Only accept date, favorites or retweets as value.
$orderBy = $_GET['orderBy'] ?? 'date';
if ($orderBy != 'date' && $orderBy != 'favorites' && $orderBy != 'retweets') {
 $orderBy = 'date';
}

?>

<div id='top'></div>
<p>Test</p>
<div id='search-fields'>
	<form action='search.php#start' method='get'>

		<!-- Search Word -->
		<div id='searchword'>
		<label for='searchWord'>Search: </label>
		<input type='text' name='searchWord' value='<?php echo htmlspecialchars($searchWord, ENT_QUOTES) ?>' />
		</div>

		<fieldset>
			<legend>Date range</legend>
			<label for='dateMin'>From:</label>
			<input type='date' name='dateMin' value='2009-05-04' min='2009-05-04' max='2020-06-17'>
			<label for='dateMax'>To:</label>
			<input type='date' name='dateMax' value='2020-06-17' min='2009-05-04' max='2020-06-17'>
		</fieldset>
		
		<!-- Min/Max favs -->
		<fieldset>
			<legend>Favorites</legend>
			<label for='favMin'>Min:</label>
			<input name='favMin' type='number' value='0'>
			<label for='favMax'>Max:</label>
			<input name='favMax' type='number' value='835575'>
			</fieldset>

		<!-- Min/Max retweets -->
		<fieldset>
			<legend>Retweets</legend>
			<label for='retMin'>Min:</label>
			<input name='retMin' type='number' value='0'>
			<label for='retMax'>Max:</label>
			<input name='retMax' type='number' value='302269'>
		</fieldset>

		<fieldset>
			<legend>Sort results</legend>
			<label for='orderBy'>Sort by</label>
			<select name='orderBy'>
				<option value='date'>Date</option>
				<option value='favorites'>Favorites</option>
				<option value='retweets'>Retweets</option>
			</select>

			<select name='orderType'>
				<option value='ASC'>ascending</option>
				<option value='DESC'>descending</option>
			</select>
		</fieldset>

		<label for='limit'>Limit</label>
		<input name='limit' type='number' value='100'>

		<input type='submit'>
	</form>
</div>

<a class='arrow' href='#top'><div class='stick'><i></i></div></a>

<?php 



// Prepare statement.
$searchquery = $db->prepare("
	SELECT content, date, retweets, favorites FROM realdonaldtrump 
	WHERE content LIKE :search 
	AND favorites BETWEEN :favMin AND :favMax 
	AND retweets BETWEEN :retMin AND :retMax
	AND date(date) BETWEEN :dateMin AND :dateMax 
	ORDER BY $orderBy 
	LIMIT :limit
"); 

// Bind parameter.
$searchquery->bindParam(':search', $search, SQLITE3_TEXT);
$searchquery->bindParam(':favMin', $favMin, SQLITE3_INTEGER);
$searchquery->bindParam(':favMax', $favMax, SQLITE3_INTEGER);
$searchquery->bindParam(':retMin', $retMin, SQLITE3_INTEGER);
$searchquery->bindParam(':retMax', $retMax, SQLITE3_INTEGER);
$searchquery->bindParam(':dateMin', $dateMin, SQLITE3_TEXT);
$searchquery->bindParam(':dateMax', $dateMax, SQLITE3_TEXT);
$searchquery->bindParam(':orderBy', $orderBy, SQLITE3_TEXT);
$searchquery->bindParam(':limit', $limit, SQLITE3_INTEGER);

$result = $searchquery->execute();

echo "<div id='start'></div><br><br><br>";

var_dump($_GET);

while( $row = $result->fetchArray(SQLITE3_ASSOC) ) { 
	theTweet($row);
}

print_foot();


