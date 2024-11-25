<?php
include_once 'models/Author.php';

class AuthorController extends Controller
{
    private $author;

    public function __construct()
    {
        $this->author = new Author();
    }

    public function index()
    {
        $authors = $this->author->read();
        $content = 'views/authors/index.php';
        include('views/layouts/base.php');
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Store form data in session before processing
                $_SESSION['form_data'] = $_POST;

                foreach ($_POST as $key => $value) {
                    if (property_exists($this->author, $key)) {
                        $this->author->$key = strip_tags(trim($value));
                    }
                }

                if ($this->author->create()) {
                    $_SESSION['message'] = 'Author created successfully!';
                    $_SESSION['message_type'] = 'success';
                    unset($_SESSION['form_data']); // Clear form data on success
                    header("Location: index.php?model=author&action=index");
                    exit();
                } else {
                    throw new Exception('Failed to create author');
                }
            } catch (Exception $e) {
                $_SESSION['message'] = 'Error creating author. Please try again!';
                $_SESSION['message_type'] = 'danger';
                $content = 'views/authors/create.php';
                include('views/layouts/base.php');
                return;
            }
        }

        $content = 'views/authors/create.php';
        include('views/layouts/base.php');
    }

    public function edit($id)
    {
        $author = $this->author->readById($id);
        if (!$author) {
            $_SESSION['message'] = 'Author not found!';
            $_SESSION['message_type'] = 'danger';
            header("Location: index.php?model=author&action=index");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                foreach ($_POST as $key => $value) {
                    if (property_exists($this->author, $key)) {
                        $this->author->$key = strip_tags(trim($value));
                    }
                }

                if ($this->author->update($id)) {
                    $_SESSION['message'] = 'Author updated successfully!';
                    $_SESSION['message_type'] = 'success';
                } else {
                    throw new Exception('Failed to update author');
                }

                header("Location: index.php?model=author&action=index");
                exit();
            } catch (Exception $e) {
                $_SESSION['message'] = 'Error updating author. Please try again!';
                $_SESSION['message_type'] = 'danger';
            }
        }

        $content = 'views/authors/edit.php';
        include('views/layouts/base.php');
    }

    public function delete($id)
    {
        try {
            if ($this->author->delete($id)) {
                $_SESSION['message'] = 'Author deleted successfully!';
                $_SESSION['message_type'] = 'success';
            } else {
                throw new Exception('Failed to delete author');
            }
        } catch (Exception $e) {
            $_SESSION['message'] = $e->getMessage();
            $_SESSION['message_type'] = 'danger';
        }

        header("Location: index.php?model=author&action=index");
        exit();
    }

    public function statistics()
    {
        // Add statistics logic here if needed
    }
}