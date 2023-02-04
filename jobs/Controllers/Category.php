<?php

namespace jobs\Controllers;

class Category
{
    private $categoriesTable;


    private $jobTable;


    public function __construct($categoriesTable)
    {

        $this->categoriesTable = $categoriesTable;


    }

    public function apply($jobs)
    {
        $success = '';
        $variable1 = 'id';
        $jobs = $this->jobTable->find($variable1, $_GET['id']);


        return ['templates' => 'applicants.html.php', 'title' => 'Apply', 'variables' => ['jobs' => $jobs, 'success' => $success]];

    }

    public function list()
    {
        $success = '';
//        $variable1 = 'id';
        $jobs = $this->categoriesTable->findAll();


        return ['templates' => 'categorylist.html.php', 'title' => 'Apply', 'variables' => ['jobs' => $jobs]];

    }

    public function applySubmit()
    {
        $errors = [];
        $variable1 = 'id';
        $parts = explode('.', $_FILES['cv']['name']);

        $extension = end($parts);

        $fileName = uniqid() . '.' . $extension;

        move_uploaded_file($_FILES['cv']['tmp_name'], 'cvs/' . $fileName);

        $Applicant = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'details' => $_POST['details'],
            'jobId' => $_POST['jobId'],
            'cv' => $fileName
        ];


        $variable1 = 'id';
        $jobs = $this->jobTable->find($variable1, $_POST['jobId']);


        $errors += $this->testApplyInvalidCv($Applicant);
        $errors += $this->testApplyInvalidEmail($Applicant);
        $errors += $this->testApplyInvalidName($Applicant);

        if (count($errors) == 0) {
            $success = $this->applicantsTable->insert($Applicant);
            $response = ' Your application has been submitted';
        } else {
            $success = array_values($errors);
            $response = ' Your application has not been submitted';

        }
        return ['templates' => 'applicants.html.php', 'title' => 'Apply', 'variables' => ['success' => $success, 'response' => $response, 'jobs' => $jobs]];

    }
//        }

//    displays all of the applicants who have applied for a given job
    public function applicantlist()
    {
        $this->loggedIn();

        $variable1 = 'jobId';
        $condition = '=';
        $variable2 = 'id';
        $applicants = $this->applicantsTable->findOtherJobs($variable1, $condition, $_GET['id']);


        return ['templates' => 'applicantList.html.php', 'title' => 'Applicants', 'variables' => ['applicants' => $applicants]];

    }


    public function deleteCategory()
    {
        $this->loggedIn();
        $this->categoriesTable->delete($_GET['categoryId']);

        header('location: /category/list');
    }

//    public function
    public function addCategory()
    {
        $success = '';
        $this->loggedIn();
        if (isset($_GET['categoryId'])) {
            $job = $this->categoriesTable->find('id', $_GET['categoryId'])[0];
        } else {
            $job = array();
        }

        return ['templates' => 'addCategory.html.php',
            'title' => 'Add a job',
            'variables' => ['job' => $job, 'success' => $success
            ]
        ];
    }

    public function editCategory()
    {
        $success = '';
        $this->loggedIn();
        if (isset($_GET['categoryId'])) {
            $job = $this->categoriesTable->find('id', $_GET['categoryId'])[0];
        } else {
            $job = array();
        }

        return ['templates' => 'addCategory.html.php',
            'title' => 'Add a job',
            'variables' => ['job' => $job, 'success' => $success
            ]
        ];
    }

    public function editCategorySubmit()
    {
        $success = 'Category updated';
        $this->loggedIn();
        $Applicant = [
            'id' => $_GET['categoryId'],
            'name' => $_POST['name']
        ];

        $newCat = $this->categoriesTable->update($Applicant);
        $success =0;
        $response = 'Category Updated';
        return ['templates' => 'addCategory.html.php',
            'title' => 'Add a job',
            'variables' => ['success' => $success, 'response'=>$response
            ]
        ];
    }


    public function addCategorySubmit()
    {

        $this->loggedIn();
        $errors = [];
        $job = ['name' => $_POST['name']];

        $errors += $this->testAddCategory($job);

        if (count($errors) == 0) {
            $success = $this->categoriesTable->insert($job);
            $response = 'Your job has been added';
        } else {
            $success = array_values($errors);
            $response = 'Your submission could not be submitted';
        }
        return ['templates' => 'addCategory.html.php',
            'title' => 'Add a job',
            'variables' => ['success' => $success, 'response' => $response
            ]
        ];
    }


    public function loggedIn()
    {
        if ($_SESSION['AdminLoggedIn'] == true || $_SESSION['ClientLoggedIn'] == true || $_SESSION['StaffLoggedIn'] == true) {

        } else {
            header('location: /User/login');
            exit();
        }
    }

    public function testValidateApply($job)
    {
        $errors = [];

        if ($job['name'] == '') {
            $errors[] = 'You must enter an name ';
        }
        if ($job['email'] == '') {
            $errors[] = 'You must enter an name ';
        }
        if ($job['details'] == '') {
            $errors[] = 'You must enter an name ';
        }
        if ($job['jobId'] == '') {
            $errors[] = 'You must enter an name ';
        }


    }

    public function testApplyInvalidName($job)
    {
        $errors = [];
        if ($job['name'] == '') {
            $errors[] = 'You must enter a value';
        }
        return $errors;
    }

    public function testApplyInvalidEmail($job)
    {
        $errors = [];
        if ($job['email'] == '') {
            $errors[] = 'You must enter a value';
        }
        return $errors;
    }

    public function testApplyInvalidCv($job)
    {
        $errors = [];
        if ($job['cv'] == '') {
            $errors[] = 'You must enter a value';
        }
        return $errors;
    }

    public function testAddCategory($job)
    {
        $errors = [];
        if ($job['name'] == '') {
            $errors[] = 'You must enter a name';
        }
        return $errors;
    }
}

//        docker compose run -w /websites/as2-1 phpunit .


