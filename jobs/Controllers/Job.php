<?php
namespace jobs\Controllers;
//class Routes implements \CSY2028\Routes
session_start();
class Job
{
    private $pdo;
    private $jobTable;

    public function __construct($jobTable)
    {
        $this->pdo = $pdo ?? "";
//        $this->applicantsTable = $applicantsTable;
        $this->jobTable = $jobTable;
//        $this->applicantsTable = $applicantsTable;
//        $this->enquiry = $enquiry;
    }

    public function home()
    {
        if ($_SESSION['ClientLoggedIn'] == false){
            $_SESSION['ClientLoggedIn'] = false;
        }else  {
            $_SESSION['ClientLoggedIn'] = true;
        }

        if ($_SESSION['AdminLoggedIn'] == false){
            $_SESSION['AdminLoggedIn'] = false;
        }else  {
            $_SESSION['AdminLoggedIn'] = true;
        }

        if ($_SESSION['StaffLoggedIn'] == false){
            $_SESSION['StaffLoggedIn'] = false;
        }else  {
            $_SESSION['StaffLoggedIn'] = true;
        }

        if (isset($_SESSION['userId'])){
            $_SESSION['userId'];
        }else  {
            $_SESSION['userId'] = false;
        }

        $variable1 = 'closingDate';
        $orderBy = 'ASC';
        $jobs = $this->jobTable->endingSoon($variable1,$orderBy);

        return ['templates' => 'index.html.php',
            'title' => 'Home',
            'variables' => ["jobs" => $jobs]
        ];
    }

//    displays each available category to filter jobs by



    public function jobs()
    {

        $variable = 'userId';
        $condition = '!=';
//        if the user is logged in , it will display all of the jobs bar the jobs the logged in user has posted
        if (isset($_SESSION['userId'])) {

            $jobs = $this->jobTable->findOtherJobs($variable, $condition);
//else it will show all of the jobs posted
        } else {
            $jobs = $this->jobTable->getJobsByCategory();
        }
        return ['templates' => 'job.html.php', 'title' => ' Job', 'variables' => ['jobs' => $jobs]];
    }

    public function locationslist()
    {
        $jobs = $this->jobTable->uniqueValues();
        return ['templates' => 'Alljobs.html.php', 'title' => ' Job', 'variables' => ['jobs' => $jobs]];


    }
//displays the lists of jobs in a specified area
    public function locationFilter()
    {
        $variable = 'location';
        $jobs = $this->jobTable->findX($variable);
        return ['templates' => 'jobByLocation.html.php', 'title' => 'Find jobs by location', 'variables' => ['jobs' => $jobs]];
    }


    public function about()
    {
        return ['templates' => 'about.html.php', 'title' => 'About', 'variables' => []];
    }

    public function FAQ()
    {
        return ['templates' => 'FAQ.html.php', 'title' => 'FAQs', 'variables' => []];
    }

    public function list()
    {
        $jobs = $this->jobTable->findAll();

        return ['templates' => 'JobsList.html.php',
            'title' => 'Job list',
            'variables' => ['jobs' => $jobs

            ]
        ];
    }

    public function addJobs()
    {
        $this->isAdminLogged();
        $this->isStaffLogged();
        $this->isClientLogged();
if (isset($_GET['id'])){
    $jobs = $this->jobTable->find('id', $_GET['id']);
}
else {
    $jobs = array();
}
        return ['templates' => 'addJob.html.php',
            'title' => 'Add a job',
            'variables' => ['jobs' => $jobs
            ]
        ];
    }

    public function addJobsSubmit()
    {

        if (isset($_POST['submit'])) {
            $job = ['title' => $_POST['title'],
                'Description' => $_POST['description'],
                'Salary' => $_POST['salary'],
                'Location' => $_POST['location'],
                'categoryId' => $_POST['categoryId'],
                'closingDate' => $_POST['closingDate'],
                'userId' => $_SESSION['userId']

            ];
            $newJob = $this->jobTable->insert($job);


        }
        $success = 'Your job has been added';
        return ['templates' => 'submissionPage.html.php',
            'title' => 'Add a job',
            'variables' => ['success' => $success
            ]
        ];
    }

// displays all of the logged in users jobs posted
    public function myJobs()
    {
        $variable = 'userId';
        $condition = '=';
        $variable2 = $_SESSION[$variable];
        $jobs = $this->jobTable->findOtherJobs($variable, $condition, $variable2);


        return ['templates' => 'job.html.php',
            'title' => 'Add user',
            'variables' => ['jobs' => $jobs
            ]
        ];

    }


    public function edit()
    {
        $this->isAdminLogged();
        $this->isStaffLogged();
        $this->isClientLogged();

        $variable1 = 'id';
        $jobs = $this->jobTable->find($variable1, $_GET['id']);

        return ['templates' => 'editJob.html.php', 'title' => ' Job', 'variables' => ['jobs' => $jobs]];

    }


    public function editSubmit()
    {
        $this->isAdminLogged();
        $this->isStaffLogged();
        $this->isClientLogged();
        if (isset($_POST['submit'])) {

            $job =
                ['id' => $_GET['id'],
                    'title' => $_POST['title'],
                    'Description' => $_POST['description'],
                    'Salary' => $_POST['salary'],
                    'Location' => $_POST['location'],
                    'categoryId' => $_POST['categoryId'],
                    'closingDate' => $_POST['closingDate'],
                    'userId' => $_SESSION['userId']

                ];
            $newJob = $this->jobTable->update($job);
        }

        $success = 'Your job has been updated';

        return ['templates' => 'submissionPage.html.php', 'title' => ' Job', 'variables' => ['success' => $success]];

    }


    public function delete() {
        $this->isAdminLogged();
        $this->isStaffLogged();
        $this->isClientLogged();
        $this->jobTable->delete($_GET['id']);

        header('location: /job/locationslist');
    }

    public function archive() {
        $this->jobTable->archive($_GET['id']);
        header('location: /admin/list');
    }

    public function unarchive()
    {
        $this->isAdminLogged();
        $this->isStaffLogged();
        $this->isClientLogged();
        $this->jobTable->unarchive($_GET['id']);
        header('location: /User/list');
    }

public function isAdminLogged(){
    if (!$_SESSION['AdminLoggedIn']) {
        header('location: /User/login');
        exit();
    }
}
    public function isClientLogged(){
        if (!$_SESSION['ClientLoggedIn']) {
            header('location: /User/login');
            exit();
        }
    }
    public function isStaffLogged(){
        if (!$_SESSION['StaffLoggedIn']) {
            header('location: /User/login');
            exit();
        }
    }



}