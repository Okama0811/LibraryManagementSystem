<?php
include_once 'models/Role.php';
include_once 'models/Permission.php';

class RoleController extends Controller
{
    private $role;
    private $permission;

    public function __construct()
    {
        $this->role = new Role();
        $this->permission = new Permission();
    }

    public function index()
    {
        $roles = $this->role->read();
        $content = 'views/roles/index.php';
        include('views/layouts/base.php');
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $_SESSION['form_data'] = $_POST;
                
                foreach ($_POST as $key => $value) {
                    if (property_exists($this->role, $key)) {
                        $this->role->$key = strip_tags(trim($value));
                    }
                }

                if ($this->role->create()) {
                    if (isset($_POST['permissions']) && is_array($_POST['permissions'])) {
                        $this->role->updatePermissions($_POST['permissions']);
                    }

                    $_SESSION['message'] = 'Role created successfully!';
                    $_SESSION['message_type'] = 'success';
                    unset($_SESSION['form_data']);
                    header("Location: index.php?model=role&action=index");
                    exit();
                } else {
                    throw new Exception('Failed to create role');
                }
            } catch (Exception $e) {
                $_SESSION['message'] = 'Role creation failed. Please try again!';
                $_SESSION['message_type'] = 'danger';
                $permissions = $this->permission->read();
                $content = 'views/roles/create.php';
                include('views/layouts/base.php');
                return;
            }
        }

        $permissions = $this->permission->read();
        $content = 'views/roles/create.php';
        include('views/layouts/base.php');
    }

    public function edit($id)
    {
        $role = $this->role->readById($id);
        if (!$role) {
            $_SESSION['message'] = 'Role not found!';
            $_SESSION['message_type'] = 'danger';
            header("Location: index.php?model=role&action=index");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                foreach ($_POST as $key => $value) {
                    if (property_exists($this->role, $key)) {
                        $this->role->$key = strip_tags(trim($value));
                    }
                }

                if ($this->role->update($id)) {
                    if (isset($_POST['permissions'])) {
                        $this->role->updatePermissions($_POST['permissions']);
                    }

                    $_SESSION['message'] = 'Role updated successfully!';
                    $_SESSION['message_type'] = 'success';
                    header("Location: index.php?model=role&action=index");
                    exit();
                } else {
                    throw new Exception('Failed to update role');
                }
            } catch (Exception $e) {
                $_SESSION['message'] = 'Role update failed. Please try again!';
                $_SESSION['message_type'] = 'danger';
            }
        }

        $permissions = $this->permission->read();
        $rolePermissions = $this->role->getPermissions();
        $content = 'views/roles/edit.php';
        include('views/layouts/base.php');
    }

    public function delete($id)
    {
        try {
            if ($this->role->hasUsers($id)) {
                throw new Exception('Cannot delete role: There are users assigned to this role.');
            }

            if ($this->role->delete($id)) {
                $_SESSION['message'] = 'Role deleted successfully!';
                $_SESSION['message_type'] = 'success';
            } else {
                throw new Exception('Failed to delete role!');
            }
        } catch (Exception $e) {
            $_SESSION['message'] = $e->getMessage();
            $_SESSION['message_type'] = 'danger';
        }

        header("Location: index.php?model=role&action=index");
        exit();
    }
}