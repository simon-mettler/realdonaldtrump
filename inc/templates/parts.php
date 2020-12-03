<?php 

function print_head($head_title){
  ?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $head_title ?></title>

    <link rel="stylesheet" type="text/css" href="style/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"> 

    <link rel="stylesheet" type="text/css" href="<?php echo URL . '/style/style.css' ?>">
  </head>

  <body>

  <?php

}

function print_foot() {
	echo '</body></html>';
}
