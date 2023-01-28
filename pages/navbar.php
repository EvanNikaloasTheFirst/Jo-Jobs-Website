<?php
use as2\DatabaseTable;
require "../Database.php";
//$pdo = new PDO('mysql:host=mysql;dbname=job;charset=utf8', 'student', 'student');

$pdo = $pdo ?? "" ;
$databaseCategory = new DatabaseTable($pdo,'category','id');
$category = $databaseCategory->findAll();

echo '<a class="categoryLink" href="/category/list"><li>Category list</li></a>';
echo '<a class="categoryLink" href="/job/locationslist"><li>Filter job by location</li></a>';
echo '<a class="categoryLink" href="/job/addJobs"><li>Add jobs</li></a>';


foreach ($category as $categoryData) {
    echo '<a class="categoryLink" href="/category/jobs?categoryId=' . $categoryData['id'] . '"><li>' . $categoryData['name'] . '</li></a>';
}

?>
