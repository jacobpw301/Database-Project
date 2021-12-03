<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="utf-8">
	<title>Example Website</title>
	<link rel="stylesheet" href="styles.css" />
</head>
<body>
<header>
	<h1>Example Website</h1>
</header>
<nav>
	<?php include_once "menu.php"; ?>
</nav>
<section>
	<h2><?php echo $pagetitle; ?></h2>
	<article>