<?php
namespace jobs\Controllers;
//session_start();
class Category
{
    private $categoriesTable;
    private $jobTable;

    private $enquiry;
    public function __construct($categoriesTable )
    {
//        $this->get = $get;
//        $this->post = $post;
        $this->categoriesTable = $categoriesTable;

    }

//    home page displays the 10< soonest ending jobs aswell as checking if any user is logged into an account

//    displays each available category to filter jobs by
    public function jobs()
    {
        $jobs = $this->categoriesTable->getJobsByCategory();
        return ['templates' => 'job.html.php', 'title' => ' Job', 'variables' => ["jobs" => $jobs]];



    }

    public function list() {

        $categories = $this->categoriesTable->findAll();

        return ['templates' => 'categorylist.html.php',
            'title' => 'Category List',
            'variables' => [
                'categories' => $categories
            ]
        ];

    }



    public function deleteCategory()
    {
        $this->isStaffLogged();

        $this->categoriesTable->delete($_GET['categoryId']);

        header('location: /category/list');
    }


    public function editCategory()
    {
        $this->isStaffLogged();

        $variable1 = 'id';
        $categorie = $this->categoriesTable->find($variable1,$_GET['categoryId']);


        return['templates' => 'addCategory.html.php','title' => 'Edit category', 'variables' => ['categorie' => $categorie ]];

    }
//    }

    public function editCategorySubmit(){
        $this->isStaffLogged();
        $job =
            ['id'=> $_POST['id'],
            'name'=> $_POST['name']];
        $newJob = $this->categoriesTable->update($job);
        $success = 'Your job has been updated';

return['templates' => 'addCategory.html.php','title' => ' Edit category', 'variables' => ['success' =>   $success ]];
    }



    public function addCategory(){
        $errors =[];
        $success = '';
        $this->isStaffLogged();
        if (isset($_GET['id'])){
            $categories = $this->categoriesTable->find('id', $_GET['id'])[0];
        }
else {
    $categories = array();
}
return['templates' => 'addCategory.html.php','title' => 'Add category', 'variables' => ['categories' => $categories, 'success' => $success]];

}




public function addCategorySubmit(){
        $this->isStaffLogged();
        $job = ['name'=> $_POST['name']];
        $errors = $this->testAddCategory($job);
        if (count($errors) == 0) {
            $success = $this->categoriesTable->insert($job);
            $response = 'Your category has been added';
        }
        else {
            $success = array_values($errors);
            $response = 'Your category couldn not  be added';

        }


        return['templates' => 'addCategory.html.php','title' => 'Add category', 'variables' => ['success' =>   $success, 'response'=>$response ]];


    }

    public function testAddCategory($job){
        $errors = [];
        if ($job['name'] == ''){
            $errors[] = 'You must enter a value';
        }

        return $errors;
    }
    public function isStaffLogged(){
        if ($_SESSION['StaffLoggedIn'] == false &&  $_SESSION['AdminLoggedIn'] == false) {
            header('location: /User/login');
            exit();
        }
    }

}
