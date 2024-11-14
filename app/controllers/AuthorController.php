<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Abstracts\Controller;
use App\Models\Author;

class AuthorController extends Controller
{
    protected $model;

    public function __construct()
    {
        $this->model = new Author();
    }

    public function index()
    {
        $books = $this->model->all();
        return $this->render('book.index', compact('books'));
    }

    public function create()
    {
        return $this->render('book.create');
    }

    public function store(array $data)
    {
        $book = $this->model->create($data);
        return $this->redirectToIndex($book);
    }

    // Các phương thức show(), edit(), update(), destroy() tương tự
}