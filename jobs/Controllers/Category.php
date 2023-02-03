<?php

namespace jobs\Controllers;

class Category
{
    private $applicantsTable;


    private $jobTable;


    public function __construct($Category)
    {

        $this->Category = $Category;


    }

    public function apply($jobs)
    {
        $success = '';
        $variable1 = 'id';
        $jobs = $this->jobTable->find($variable1, $_GET['id']);


        return ['templates' => 'applicants.html.php', 'title' => 'Apply', 'variables' => ['jobs' => $jobs, 'success' => $success]];

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
//                $errors += $this->testValidateApply($Applicant);

        if (count($errors) == 0) {
            $success =$this->applicantsTable->insert($Applicant);
            $response = ' Your application has been submitted';
        } else {
            $success = array_values($errors);
            $response = ' Your application has not been submitted';

        }
        return ['templates' => 'applicants.html.php', 'title' => 'Apply', 'variables' => ['success' => $success, 'response' => $response,'jobs'=>$jobs]];

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

    public function testApplyInvalidName($job){
        $errors = [];
        if ($job['name'] == ''){
            $errors[] = 'You must enter a value';
        }
        return $errors;
    }
    public function testApplyInvalidEmail($job){
        $errors = [];
        if ($job['email'] == ''){
            $errors[] = 'You must enter a value';
        }
        return $errors;
    }
    public function testApplyInvalidCv($job){
        $errors = [];
        if ($job['cv'] == ''){
            $errors[] = 'You must enter a value';
        }
        return $errors;
    }

}
