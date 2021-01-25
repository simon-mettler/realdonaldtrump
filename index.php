<?php

include_once('inc/settings.php');
include_once('inc/functions.php');

printHead("@realDonaldTrump");
?>

		<div class="info">
			<h1>@realDonaldTrump</h1>
			<p>Das @realDonaldTrump Archiv enthält 43352 Tweets von Donald Trump aus dem Zeitraum vom Mai 2009 bis Juni 2020.</p>
			<p>Die Tweets können nach einzelnen Wörtern und Wortgruppen, mittels @ nach Mentions (@cnn) und mittels # nach Hashtags (#MAGA) durchsucht werden. Eine hohe Limite sowie die Wortstatistik kann zu längeren Ladezeigen führen.</p>
			<a id="cta" href="search.php">Tweets durchsuchen</a>
		</div>

<?php 

// Random 'best of' tweet.
$bestOf = array('332308211321425920', '645763590981611524', '491324429184823296', '713747213801938946', '408977616926830592');
$randomKey= array_rand($bestOf, 1);

// Open db.
$db = new SQLite3('./db/realdonaldtrump.db');

// Prepare statement.
$searchquery = $db->prepare("SELECT content, date, retweets, favorites FROM realdonaldtrump WHERE id = $bestOf[$randomKey]"); 

// Print single tweet.
$result = $searchquery->execute();
printTweet($result->fetchArray(SQLITE3_ASSOC));

?>

	</body>
</html>

