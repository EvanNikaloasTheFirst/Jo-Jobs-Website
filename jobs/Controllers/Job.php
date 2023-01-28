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
//        $this->get = $get;
//        $this->post = $post;
        $this->pdo = $pdo ?? "";
        $this->jobTable = $jobTable;

    }

    public function home()
    {
        $_SESSION['ClientLoggedIn'];
        $_SESSION['AdminLoggedIn'];
        $_SESSION['userId'];
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
        return ['templates' => 'job.html.php', 'title' => 'Jobs', 'variables' => ['jobs' => $jobs]];
    }

    public function locationslist()
    {
        $variable = 'location';
        $jobs = $this->jobTable->uniqueValues($variable);
        return ['templates' => 'Alljobs.html.php', 'title' => 'Jobs', 'variables' => ['jobs' => $jobs]];


    }
    //displays the lists of jobs in a specified area
    public function locationFilter()
    {
        $variable = 'location';
        $jobs = $this->jobTable->findX($variable);
        return ['templates' => 'job.html.php', 'title' => 'Find jobs by location', 'variables' => ['jobs' => $jobs]];
    }

    // Display the about page
    public function about()
    {
        return ['templates' => 'about.html.php', 'title' => 'About', 'variables' => []];
    }
    // Display the FAQs page
    public function FAQ()
    {
        return ['templates' => 'FAQ.html.php', 'title' => 'FAQs', 'variables' => []];
    }
    // Displays the list of  (all) jobs page
    public function list()
    {
        $this->loggedIn();
        $jobs = $this->jobTable->findAll();

        return ['templates' => 'job.html.php',
            'title' => 'Job list',
            'variables' => ['jobs' => $jobs

            ]
        ];
    }

    //    Allows logged in user to add a job
    public function addJobs()
    {
        $success = '';
        $this->loggedIn();
        if (isset($_GET['id'])){
            $job = $this->jobTable->find('id', $_GET['id'])[0];
        }
        else {
            $job = array();
        }

        return ['templates' => 'addJob.html.php',
            'title' => 'Add a job',
            'variables' => ['job' => $job, 'success'=> $success
            ]
        ];
    }



//    When the submit button is clicked this function is run
    public function addJobsSubmit()
    {

        $this->loggedIn();
        $errors = [];
            $job = ['title' => $_POST['title'],
                'Description' => $_POST['description'],
                'Salary' => $_POST['salary'],
                'Location' => $_POST['location'],
                'categoryId' => $_POST['categoryId'],
                'closingDate' => $_POST['closingDate'],
                'userId' => $_SESSION['userId']];

            $errors += $this->testAddJobInvalidClosingDate($job);
            $errors += $this->testAddJobBlankcategoryId($job);
            $errors += $this->testAddJobBlankLocation($job);
            $errors += $this->testAddJobBlankSalary($job);
            $errors += $this->testAddJobBlankDescription($job);
            $errors += $this->testAddJobBlankTitle($job);
            $errors += $this->testAddJobAllBlank($job);
            if (count($errors) == 0) {
               $success = $this->jobTable->insert($job);
                $response = 'Your job has been added';
            } else {
                $success = array_values($errors);
                $response = 'Your submission could not be submitted';
            }
            return ['templates' => 'addJob.html.php',
                'title' => 'Add a job',
                'variables' => ['success' => $success, 'response' => $response
                ]
            ];
        }
//    }

// displays all of the logged in users jobs posted
    public function myJobs()
    {
        $this->loggedIn();
        $variable = 'userId';
        $condition = '=';
        $variable2 = $_SESSION[$variable];
        $jobs = $this->jobTable->findOtherJobs($variable, $condition, $variable2);


        return ['templates' => 'myJobs.html.php',
            'title' => 'My jobs',
            'variables' => ['jobs' => $jobs
            ]
        ];

    }

// This function allows the user to edit the job listing
    public function edit()
    {
        $success = '';
        $this->loggedIn();
        $variable1 = 'id';
        $jobs = $this->jobTable->find($variable1, $_GET['id']);

        return ['templates' => 'addCategory.html.php', 'title' => 'Edit job', 'variables' => ['jobs' => $jobs,'success'=> $success]];

    }

//    When the submit button is clicked this function is run
    public function editSubmit()
    {
        $this->loggedIn();
        $errors=[];

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
                $success = 'Your job has been updated';
        return ['templates' => 'addCategory.html.php', 'title' => ' Edit job', 'variables' => ['success' => $success]];

    }
// Deletes the selected job
    public function delete() {
        $this->loggedIn();
        $this->jobTable->delete($_GET['id']);

        header('location: /job/locationslist');
    }

    public function archive() {
        $this->loggedIn();
        $variable = 'Archived';
        $this->jobTable->archive($variable);
        header('location: /job/list');
        $success = 'Your job has been archived';

    }

    public function unarchive()
    {
        $this->loggedIn();
        $variable = 'Archived';
        $this->jobTable->unarchive($variable);
        header('location: /job/list');
        $success = 'Your job has been unarchvied';

    }

    public function testAddJobAllBlank($job){
        $errors = [];
        if ($job['title'] == ''){
            $errors[] = 'You must enter a title';
        }

        if ($job['Description'] == ''){
            $errors[] = 'You must enter a Description';
        }
        if ($job['Salary'] == ''){
            $errors[] = 'You must enter a Salary';
        }
        if ($job['Location'] == ''){
            $errors[] = 'You must enter a Location';
        }
        if ($job['categoryId'] == ''){
            $errors[] = 'You must enter a category';
        }
        if ($job['closingDate'] == ''){
            $errors[] = 'You must enter a closing date';
        }
        return $errors;
//        docker compose run -w /websites/as2-1 phpunit .


    }

    public function testAddJobBlankTitle($job){
        $errors = [];
        if ($job['title'] == ''){
            $errors[] = 'You must enter a value';
        }
        return $errors;
    }

    public function testAddJobBlankDescription($job){
        $errors = [];
        if ($job['Description'] == ''){
            $errors[] = 'You must enter a value';
        }
        return $errors;
    }

    public function testAddJobBlankSalary($job){
        $errors = [];
        if ($job['Salary'] == ''){
            $errors[] = 'You must enter a value';
        }
        return $errors;
    }
    public function testAddJobBlankLocation($job){
        $errors = [];
        if ($job['Location'] == '') {
            $errors[] = 'You must enter a value';
        }
        return $errors;
    }

    public function testAddJobBlankcategoryId($job){
        $errors = [];
        if ($job['categoryId'] == '') {
            $errors[] = 'You must enter a value';
        }
        return $errors;
    }

    public function testAddJobInvalidClosingDate($job){
        $errors = [];
        if ($job['closingDate'] == 'DD/MM/YYY' || '') {
            $errors[] = 'You must enter a value';
        }
        return $errors;
    }

//    Log in checks -> Checks which type of user is logged in if any user is logged in
    public function loggedIn(){


        if ($_SESSION['StaffLoggedIn'] == true || $_SESSION['ClientLoggedIn'] == true || $_SESSION['AdminLoggedIn'] == true) {

        }
        else {
            header('location: /User/login');
            exit();
        }
    }


}