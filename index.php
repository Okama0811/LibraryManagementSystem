<?php
require 'controllers/abstract/Controller.php';
require 'controllers/AuthorController.php';
require 'controllers/AuthController.php';
require 'controllers/BookController.php';
require 'controllers/BookConditionController.php';
require 'controllers/CategoryController.php';
require 'controllers/UserController.php';
require 'controllers/FineController.php';
require 'controllers/LoanController.php';
require 'controllers/PermissionController.php';
require 'controllers/PublisherController.php';
require 'controllers/ReservationController.php';
require 'controllers/RoleController.php';
require 'controllers/DefaultController.php';


$model = isset($_GET['model']) ? $_GET['model'] : 'default';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';
$id = isset($_GET['id']) ? $_GET['id'] : null;

$public_routes = [
    'default' => ['index'],
    'auth' => ['login', 'register', 'register_success']
];

$is_public_route = isset($public_routes[$model]) && in_array($action, $public_routes[$model]);

if (!isset($_SESSION['user_id']) && !$is_public_route) {
    header('Location: index.php?model=auth&action=login');
    exit();
}

if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 3) {
    if ($model !== 'default' && $model !== 'auth') {
        header('Location: index.php?model=default&action=index');
        exit();
    }
}

switch ($model) {
    case 'default':
        $controller = new DefaultController();
        break;
    case 'role':
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] == 3) {
            header('Location: index.php?model=default&action=index');
            exit();
        }
        $controller = new RoleController();
        break;
    case 'permission':
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] == 3) {
            header('Location: index.php?model=default&action=index');
            exit();
        }
        $controller = new PermissionController();
        break;
    case 'user':
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] == 3) {
            header('Location: index.php?model=default&action=index');
            exit();
        }
        $controller = new UserController();
        break;
    case 'book':
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] == 3) {
            header('Location: index.php?model=default&action=index');
            exit();
        }
        $controller = new BookController();
        break;
    case 'book_condition':
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] == 3) {
            header('Location: index.php?model=default&action=index');
            exit();
        }
        $controller = new BookConditionController();
        break;
    case 'fine':
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] == 3) {
            header('Location: index.php?model=default&action=index');
            exit();
        }
        $controller = new FineController();
        break;
    case 'reservation':
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] == 3) {
            header('Location: index.php?model=default&action=index');
            exit();
        }
        $controller = new ReservationController();
        break;
    case 'auth':
        $controller = new AuthController();
        break;
    case 'author':
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] == 3) {
            header('Location: index.php?model=default&action=index');
            exit();
        }
        $controller = new AuthorController();
        break;
    case 'loan':
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] == 3) {
            header('Location: index.php?model=default&action=index');
            exit();
        }
        $controller = new LoanController();
        break;
    case 'publisher':
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] == 3) {
            header('Location: index.php?model=default&action=index');
            exit();
        }
        $controller = new PublisherController();
        break;
    case 'category':
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] == 3) {
            header('Location: index.php?model=default&action=index');
            exit();
        }
        $controller = new CategoryController();
        break;
    default:
        $controller = new DefaultController();
        break;
}

switch ($action) {
    case 'create':
        $controller->create();
        break;
    case 'edit':
        $controller->edit($id);
        break;
    case 'delete':
        $controller->delete($id);
        break;
    case 'show':
        $controller->show($id);
        break;
    case 'login':
        $controller->login();
        break;
    case 'register':
        $controller->register();
        break;
    case 'logout':
        $controller->logout();
        break;
    case 'register_success':
        $controller->register_success();
        break;
    default:
        $controller->index();
        break;
}
?>