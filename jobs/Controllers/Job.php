<?php

namespace jobs\Controllers;
//class Routes implements \CSY2028\Routes
session_start();
class Job
{
    private $pdo;
    private $jobTable;
    private $applicantsTable;
    public function __construct($jobTable,$applicantsTable )
    {
//        require "../Database.php";
        $this->pdo = $pdo ?? "";
        $this->applicantsTable = $applicantsTable;
        $this->jobTable = $jobTable;
//        $this->categoriesTable = $categoriesTable;
    }

//FAQ Job ✅


    public function jobs()
    {

        $variable = 'userId';
        $condition = '!=';
        if (isset($_SESSION['userId'])) {

            $jobs = $this->jobTable->findOtherJobs($variable,$condition);

        } else {
            $jobs = $this->jobTable->getJobsByCategory();
        }
        return['templates' => 'job.html.php','title' => ' Job', 'variables' => ['jobs'=> $jobs]];
    }

    public function locationslist()
    {
        $jobs = $this->jobTable->uniqueValues();
        return['templates' => 'Alljobs.html.php','title' => ' Job', 'variables' => ['jobs'=> $jobs]];


    }

    public function locationFilter()
    {
        $variable = 'location';
        $jobs = $this->jobTable->findX($variable);
        return['templates' => 'jobByLocation.html.php','title' => 'Find jobs by location', 'variables' => ['jobs'=> $jobs]];
    }

    //about Job ✅
    public function about()
    {
        return ['templates' => 'about.html.php', 'title' => 'About', 'variables' => []];
    }


//FAQ Job

    public function FAQ()
    {
        return ['templates' => 'FAQ.html.php', 'title' => 'FAQs', 'variables' => []];
    }

    public function list() {

//        $userId = 'userId';
//        if (isset($_SESSION['userId'])) {
//
//            $jobs = $this->jobTable->findOtherJobs();
//
//        } else {
            $jobs = $this->jobTable->findAll();
//        }

        return ['templates' => 'JobsList.html.php',
            'title' => 'Job list',
            'variables' => [ 'jobs'=> $jobs

            ]
        ];
    }

    public function addJobs(){


        return ['templates' => 'addJob.html.php',
            'title' => 'Add a job',
            'variables' => [
            ]
        ];
    }

    public function addJobsSubmit(){

    if(isset($_POST['submit'])){
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
        $success  = 'Your job has been added';
        return ['templates' => 'submissionPage.html.php',
            'title' => 'Add a job',
            'variables' => ['success' =>   $success
            ]
        ];
    }

    public function register(){

        return ['templates' => 'addUser.html.php',
            'title' => 'Add user',
            'variables' => [

            ]
        ];
    }


    public function myJobs(){

        $variable = 'userId';
        $condition = '=';
        $jobs = $this->jobTable->findOtherJobs($variable,$condition);


        return ['templates' => 'job.html.php',
            'title' => 'Add user',
            'variables' => ['jobs'=> $jobs
            ]
        ];

    }


    public function edit(){

        $variable1 = 'id';
        $jobs = $this->jobTable->find($variable1,$_GET['id']);

        return['templates' => 'addJob.html.php','title' => ' Job', 'variables' => ['jobs'=> $jobs]];

    }


    public function editSubmit(){

        if(isset($_POST['submit'])) {

            $job =
                ['id'=> $_GET['id'],
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



        return['templates' => 'submissionPage.html.php','title' => ' Job', 'variables' => ['success' =>   $success ]];

    }

    public function apply(){
        $variable1 = 'id';
        $jobs = $this->jobTable->find($variable1,$_GET['id']);

        return['templates' => 'applicants.html.php','title' => ' Job', 'variables' => ['jobs'=> $jobs]];

    }


    public function applySubmit(){
        if (isset($_POST['submit'])) {
            if ($_FILES['cv']['error'] == 0) {

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
                $newApplicant = $this->applicantsTable->insert($Applicant);
                $success = ' Your CV has been submitted';

            }  else {
                echo 'There was an error uploading your CV';
            }
        }

                return['templates' => 'submissionPage.html.php','title' => ' Job', 'variables' => ['success'=> $success]];

    }

    public function applicants(){

        $jobs = $this->jobTable->find($variable1,$_GET['id']);




    }




}