<?php
//require "../DatabasesTable/Database.php";
$title = 'index';
//$joke = $database->find('id',1)[0];
$output = loadTemplate('../templates/index.html.php',['joke' => $joke]);
// $templateVariable = ['title' => $title , 'output' => $output];


?>



   