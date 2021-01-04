<?php

/* ----------------------------------------------------------
 * Settings
 * -------------------------------------------------------- */

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('URL', 'http://localhost/realdonaldtrump');


/* ----------------------------------------------------------
 * Template fuctions 
 * -------------------------------------------------------- */

// Tweet structure. 
function theTweet($row) {
	?>
	<div class="tweet">
		<span class="tweet-trump">@realDonaldTrump</span><span class="tweet-date"><?php echo $row['date'] ?></span>
		<p class="tweet-text"><?php echo $row['content'] ?></p>
		<div class="tweet-meta">
			<div class="retweets">
				<svg class="" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" 
					stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<polyline points="17 1 21 5 17 9"></polyline>
					<path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
					<polyline points="7 23 3 19 7 15"></polyline>
					<path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
				</svg>
				<div class="retweet-count"><?php echo $row['retweets'] ?></div>
			</div>
			<div class="likes">
				<svg class="" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" 
					stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 
					1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
				</svg>
				<div class="likes-count"><?php echo $row['favorites'] ?></div>
			</div>
		</div>
	</div><!-- .tweet -->
	<?php 
}


/* ----------------------------------------------------------
 * Set and escape some form values... 
 * -------------------------------------------------------- */

// Search single word case insensitive.
if (empty($_GET['searchWord'])) {
	$search = '%';
} else {
	$search = '% '.$_GET['searchWord'].' %';
}
$searchWord = $_GET['searchWord'] ?? '';

// Set max and min values.
$limit = $_GET['limit'] ?? '100';
$favMin = $_GET['favMin'] ?? '0';
$favMax = $_GET['favMax'] ?? '835575';
$retMin = $_GET['retMin'] ?? '0';
$retMax = $_GET['retMax'] ?? '302269';
$dateMin = $_GET['dateMin'] ?? '2009-05-04';
$dateMax = $_GET['dateMax'] ?? '2020-06-17';

// Only accept date, favorites or retweets as value.
$orderBy = $_GET['orderBy'] ?? 'date';
if ( $orderBy != 'date' && $orderBy != 'favorites' && $orderBy != 'retweets' ) {
 $orderBy = 'date';
}

// Only accept ASC or  DESC as value.
$orderType = $_GET['orderType'] ?? 'ASC';
if ( $orderType != 'ASC' && $orderType != 'DESC' ) {
	$orderType = 'ASC';
}


/* ----------------------------------------------------------
 * Build/prepare sql query 
 * -------------------------------------------------------- */

// Open db.
$db = new SQLite3('./db/realdonaldtrump.db');
 
// Prepare statement.
$searchquery = $db->prepare("
	SELECT content, date, retweets, favorites FROM realdonaldtrump 
	WHERE (' ' || content || ' ') LIKE :search 
	AND favorites BETWEEN :favMin AND :favMax 
	AND retweets BETWEEN :retMin AND :retMax
	AND date(date) BETWEEN :dateMin AND :dateMax 
	ORDER BY $orderBy $orderType  
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

// Fetch result.
$result = $searchquery->execute();


/* ----------------------------------------------------------
 * html structure 
 * -------------------------------------------------------- */

?>

<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>realDonaldTrump</title>

		<link rel="stylesheet" type="text/css" href="style/style.css">
		<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"> 

	</head>

	<body>

	<div id='top'></div>
	<div id='search-fields'>
		<form action='index.php#start' method='get'>

			<!-- Search Word -->
			<div id='searchword'>
			<label for='searchWord'>Search: </label>
			<input type='text' name='searchWord' value='<?php echo htmlspecialchars($searchWord, ENT_QUOTES) ?>' />
			</div>

			<!-- Date range -->
			<fieldset>
				<legend>Date range</legend>
				<label for='dateMin'>From:</label>
				<input type='date' name='dateMin' value='<?php echo htmlspecialchars($dateMin, ENT_QUOTES) ?>' min='2009-05-04' max='2020-06-17'>
				<label for='dateMax'>To:</label>
				<input type='date' name='dateMax' value='<?php echo htmlspecialchars($dateMax, ENT_QUOTES) ?>' min='2009-05-04' max='2020-06-17'>
			</fieldset>
			
			<!-- Min/Max favs -->
			<fieldset>
				<legend>Favorites</legend>
				<label for='favMin'>Min:</label>
				<input name='favMin' type='number' value='<?php echo htmlspecialchars($favMin, ENT_QUOTES) ?>'>
				<label for='favMax'>Max:</label>
				<input name='favMax' type='number' value='<?php echo htmlspecialchars($favMax, ENT_QUOTES) ?>'>
				</fieldset>

			<!-- Min/Max retweets -->
			<fieldset>
				<legend>Retweets</legend>
				<label for='retMin'>Min:</label>
				<input name='retMin' type='number' value='<?php echo htmlspecialchars($retMin, ENT_QUOTES) ?>'>
				<label for='retMax'>Max:</label>
				<input name='retMax' type='number' value='<?php echo htmlspecialchars($retMax, ENT_QUOTES) ?>'>
			</fieldset>

			<!-- Ordering -->
			<fieldset>
				<legend>Sort results</legend>
				<label for='orderBy'>Sort by</label>
				<select name='orderBy'>
					<option value='date' <?php echo ($orderBy == 'date') ? 'selected' : ''  ?>>Date</option>
					<option value='favorites' <?php echo ($orderBy == 'favorites') ? 'selected' : ''  ?>>Favorites</option>
					<option value='retweets' <?php echo ($orderBy == 'retweets') ? 'selected' : ''  ?>>Retweets</option>
				</select>

				<select name='orderType'>
					<option value='ASC'<?php echo ($orderType == 'ASC') ? 'selected' : ''  ?>>ascending</option>
					<option value='DESC'<?php echo ($orderType == 'DESC') ? 'selected' : ''  ?>>descending</option>
				</select>
			</fieldset>

			<!-- Limit -->
			<label for='limit'>Limit</label>
			<input name='limit' type='number' value='<?php echo $limit ?>'>

			<input type='submit'>
		</form>
	</div>

	<a class='arrow' href='#top'><div class='stick'><i></i></div></a>

<?php 

/* ----------------------------------------------------------
 * Print statistics and tweets 
 * -------------------------------------------------------- */

$dataStr = '';

while( $row = $result->fetchArray(SQLITE3_ASSOC) ) { 
	$dataStr .= $row['content'].' ';
}

$pyResult = shell_exec('python3 pytest.py ' . escapeshellarg($dataStr) . " 2>&1");

echo 'A total of ' . $pyResult . ' Words...';

while( $row = $result->fetchArray(SQLITE3_ASSOC) ) { 
	theTweet($row);
}

echo '</body></html>';

