<?php error_reporting(0); ?>
<title>Jo's Jobs - Home</title>
<main>
<p>Welcome to Jo's Jobs, we're a recruitment agency based in Northampton.
    We offer a range of different office jobs. Get in touch if you'd like to list a job with us.</a></p>
<h2>Listings ending soon:</h2>
<ul>
    <?php   foreach ($jobs as $job) { ?>

        <h1> <?php echo $job['title'] ?></h1>

        <?=  '<li>'; ?>

        <?=  '<div class="details">';  ?>
        <?=  '<h2>' . $job['title'] . '</h2>';  ?>
        <?=  '<h3>' . $job['salary'] . '</h3>';  ?>
        <?=  '<p>' . nl2br($job['description'])  . '</p>';  ?>

    <?php if ($_SESSION['userId'] != $job['userId'] || ($_SESSION['userId']) == 'null'  ) {?>
    <?=    '<a class="more" href="/Applicants/apply?id=' . $job['id'] . '">Apply for this job</a>'; ?>
    <?php    } else if ($_SESSION['AdminLoggedIn'] == true || $_SESSION['StaffLoggedIn'] == true ){ ?>
            <?=    '<a href="/Admin/delete?id=' . $job['id'] . '">Delete Job </a>';?>
            <?=    '<a href="/job/edit?id=' . $job['id'] . '">Edit Job </a>';?>
            <?=    '<a href="/Applicants/applicantlist?id=' . $job['id'] . '">View applicants </a>';?>

    <?php } if ($job['Archived'] == 'Y' &&  $_SESSION['AdminLoggedIn'] == true || $_SESSION['StaffLoggedIn'] == true)
    {    ?>
    <a href="/job/unarchive?id=<?=$job['id']?>">Unarchive this job</a>
<!--    --><?php //=    '<a href="/Admin/delete?id=' . $job['id'] . '">Delete Job </a>';?>
<!--    --><?php //=    '<a href="/job/edit?id=' . $job['id'] . '">Edit Job </a>';?>
    <?php } else if ($job['Archived'] == 'Y' &&  $_SESSION['AdminLoggedIn'] == true || $_SESSION['StaffLoggedIn'] == true){?>
    <a href="/job/archive?id=<?=$job['id']?>">Archive this job</a>
<!--    --><?php //=    '<a href="/Admin/delete?id=' . $job['id'] . '">Delete Job </a>';?>
<!--    --><?php //=    '<a href="/job/edit?id=' . $job['id'] . '">Edit Job </a>';?>
    <?php }?>



    <?php } ?>


    <?=  '</div>';  ?>
    <?=  '</li>';  ?>


</ul>
    </main>
