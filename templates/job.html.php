<main class="sidebar">

    <section class="right">



        <ul class="listing">
        <?php   foreach ($jobs as $job) { ?>

            <h1> <?php echo $job['title'] ?></h1>

            <?=  '<li>'; ?>

            <?=  '<div class="details">';  ?>
               <?=  '<h2>' . $job['title'] . '</h2>';  ?>
                 <?=  '<h3>' . $job['salary'] . '</h3>';  ?>
                 <?=  '<p>' . nl2br($job['description'])  . '</p>';  ?>

<?php if ($_SESSION['userId'] != $job['userId'] || ($_SESSION['userId']) == 'null') {?>
         <?=    '<a class="more" href="/job/apply?id=' . $job['id'] . '">Apply for this job</a>'; ?>
    <?php    } else { ?>

    <?=    '<a href="/Admin/delete?id=' . $job['id'] . '">Delete Job </a>';?>
    <?=    '<a href="/job/edit?id=' . $job['id'] . '">Edit Job </a>';?>

                <?=    '<a href="/job/applicantlist?id=' . $job['id'] . '">View applicants </a>';?>
   <?php } ?>


                 <?=  '</div>';  ?>
                 <?=  '</li>';  ?>
            <?php } ?>




        </ul>
    </section>
</main>