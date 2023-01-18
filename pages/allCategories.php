<?php

foreach (categories as $categoryData) {
    echo '<li><a class="categoryLink href="job?categoryId='.$categoryData['id'].'">'. $categoryData['name'].'</a></li>';
}
?>

