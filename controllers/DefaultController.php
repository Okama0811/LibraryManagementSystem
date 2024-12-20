<?php
include_once 'models/Book.php';
include_once 'models/Role.php';

class DefaultController extends Controller
{
    private $user;
    private $role;

    public function __construct()
    {
       
    }

    public function index()
    {
        $book=new Book();
        $data=$book->read();
        // var_dump($data);
        // exit();
        $content= 'views/default/index.php';
        include('views/layouts/application.php');
        // $content = 'views/users/index.php';
        // include('views/layouts/base.php');
    }
    public function admin_dashboard(){
        $content = 'views/default/adminDashboard.php';
        include('views/layouts/base.php');
    }
}