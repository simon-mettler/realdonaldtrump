<?php

include_once('inc/settings.php');
include_once('inc/functions.php');

/* ----------------------------------------------------------
 * Set and escape some form values... 
 * -------------------------------------------------------- */

// Search word(s) case insensitive.
if (empty($_GET['searchWord'])) {
	$search = '%';
} else {
	$search = '% '.$_GET['searchWord'].' %';
}
$searchWord = $_GET['searchWord'] ?? '';

// Set max and min values.
$limit = $_GET['limit'] ?? '21';
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

// Only accept ASC or DESC as value.
$orderType = $_GET['orderType'] ?? 'ASC';
if ( $orderType != 'ASC' && $orderType != 'DESC' ) {
	$orderType = 'ASC';
}

// Only accept ws.
$ws= $_GET['ws'] ?? '';
if ( $ws != 'ws' && $ws != '' ) {
	$orderType = '';
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

printHead("@realDonaldTrump")
?>

	<a id="home" href="<?php echo URL ?>">Home</a>

	<div id='top'></div>
	<div id='search-fields'>
		<form action='search.php#start' method='get'>

			<!-- Search Word -->
			<div id='searchword'>
			<label for='searchWord'>Search: </label>
			<input type='text' name='searchWord' value='<?php echo htmlspecialchars($searchWord, ENT_QUOTES) ?>' />
			</div>

			<!-- Date range -->
			<fieldset>
				<legend>Datum</legend>
				<label for='dateMin'>Von:</label>
				<input type='date' name='dateMin' value='<?php echo htmlspecialchars($dateMin, ENT_QUOTES) ?>' min='2009-05-04' max='2020-06-17'>
				<label for='dateMax'>bis:</label>
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
		  <input type="checkbox" id="ws" name="ws" value="ws">
  		<label for="ws">Wortstatistik</label><br>

			<input type='submit' value="Suchen"><a href="search.php">Abfrage zurücksetzen</a>
		</form>
	</div>

	<a class='arrow' id="start"  href='#top'><div class='stick'><i></i></div></a>

<?php 

/* ----------------------------------------------------------
 * Print statistics and tweets 
 * -------------------------------------------------------- */

if ( $result->fetchArray(SQLITE3_ASSOC) ) {
	
	if ($ws == 'ws') {

		$dataStr = '';
		while( $row = $result->fetchArray(SQLITE3_ASSOC) ) { 
			$dataStr .= $row['content'].' ';
		}

		$pyResult = shell_exec('/usr/bin/python3.8 wordstat.py ' . escapeshellarg($dataStr) . ' 2>&1');

		echo "<div class='content'><p>" . $pyResult . "</p></div>";
	}

	while( $row = $result->fetchArray(SQLITE3_ASSOC) ) { 
		printTweet($row);
	}

} else {
	echo "<div class='content'><p>Keine Tweets gefunden...</p></div>";
}

echo '</div></body></html>';

