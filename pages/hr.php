<?php
$title = 'index';
// $category = $databaseCategory->findAll();

$pdo = new PDO('mysql:dbname=job;host=mysql', 'student', 'student');
	$stmt = $pdo->prepare('SELECT * FROM job WHERE categoryId = 1 AND closingDate > :date');

	$date = new DateTime();

	$values = [
		'date' => $date->format('Y-m-d')
	];

	$stmt->execute($values);

	$output = loadTemplate('../templates/job.html.php', ['category' => $stmt]);

?>



