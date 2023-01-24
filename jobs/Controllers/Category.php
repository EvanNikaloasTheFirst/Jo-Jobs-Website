<?php
namespace jobs\Controllers;
//session_start();
class Category
{
    private $categoriesTable;
    private $jobTable;

    private $enquiry;
//$categoriesTable,$jobTable,$enquiry);
    public function __construct($categoriesTable)
    {
        $this->categoriesTable = $categoriesTable;
//        $this->jobTable = $jobTable;
//        $this->enquiry = $enquiry;
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



    public function delete()
    {
        $this->isAdminLogged();
        $this->isStaffLogged();
        $this->categoriesTable->delete($_POST['id']);

        header('location: /category/list');
    }


    public function editCategory()
    {
        $this->isAdminLogged();
        $this->isStaffLogged();

        $variable1 = 'id';
        $categories = $this->categoriesTable->find($variable1,$_GET['categoryId']);


        return['templates' => 'editCategoryForm.html.php','title' => ' Category List', 'variables' => ['categories' => $categories ]];

    }
//    }

    public function editCategorySubmit(){
        $this->isAdminLogged();
        $this->isStaffLogged();
        $job =
            ['id'=> $_POST['id'],
            'name'=> $_POST['name']];
        $newJob = $this->categoriesTable->update($job);
        $success = 'Your job has been updated';

return['templates' => 'submissionPage.html.php','title' => ' Job', 'variables' => ['success' =>   $success ]];
    }

    public function addCategory(){
        $this->isAdminLogged();
        $this->isStaffLogged();

        return['templates' => 'addCategory.html.php','title' => ' Job', 'variables' => []];

    }

    public function categorySubmit(){
        $this->isAdminLogged();
        $this->isStaffLogged();

        $job =

                ['name'=> $_POST['name']];
        $newJob = $this->categoriesTable->insert($job);
        $success = 'Your job has been updated';

        return['templates' => 'submissionPage.html.php','title' => ' Job', 'variables' => ['success' =>   $success ]];


    }

}
