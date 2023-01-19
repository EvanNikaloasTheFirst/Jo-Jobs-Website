<?php
namespace jobs\Controllers;
//session_start();
class Category
{
    private $categoriesTable;
    private $jobTable;

    public function __construct($categoriesTable,$jobTable)
    {
        $this->categoriesTable = $categoriesTable;
        $this->jobTable = $jobTable;
    }

    public function home()
    {

        $variable1 = 'closingDate';
        $orderBy = 'ASC';
        $jobs = $this->jobTable->endingSoon($variable1,$orderBy);

        return ['templates' => 'index.html.php',
            'title' => 'Home',
            'variables' => ["jobs" => $jobs]
        ];
    }

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
        $this->categoriesTable->delete($_POST['id']);

        header('location: /category/list');
    }


    public function editCategory()
    {

        $variable1 = 'id';
        $categories = $this->categoriesTable->find($variable1,$_GET['categoryId']);


        return['templates' => 'editCategoryForm.html.php','title' => ' Category List', 'variables' => ['categories' => $categories ]];

    }
//    }

    public function editCategorySubmit(){
        $job =
            ['id'=> $_POST['id'],
            'name'=> $_POST['name']];
        $newJob = $this->categoriesTable->update($job);
        $success = 'Your job has been updated';

return['templates' => 'submissionPage.html.php','title' => ' Job', 'variables' => ['success' =>   $success ]];
    }
    public function addCategory(){

        return['templates' => 'addCategory.html.php','title' => ' Category List', 'variables' => []];


    }

    public function addCategorySubmit(){
        $job =
//            ['id'=> $_POST['id'],
                ['name'=> $_POST['name']];
        $newCategory = $this->categoriesTable->insert($job);
        $success = 'Your category has been added';

        return['templates' => 'submissionPage.html.php','title' => ' Job', 'variables' => ['success' =>   $success ]];
    }
}
