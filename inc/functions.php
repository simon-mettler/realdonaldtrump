<?php 

/* ----------------------------------------------------------
 * Template fuctions 
 * -------------------------------------------------------- */

// Prints single tweet.
function printTweet($row) {
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

// Prints html head.
function printHead($title) {
	?>
	<!DOCTYPE html>
	<html lang="en">

		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<title><?php echo $title ?></title>

			<link rel="stylesheet" type="text/css" href="style/style.css">
			<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"> 

		</head>

		<body>
	<?php 
}

