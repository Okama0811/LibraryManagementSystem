<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Abstracts\Controller;
use App\Models\Book;

class BookController extends Controller
{
    protected $model;

    public function __construct()
    {
        $this->model = new Book();
    }

    public function index()
    {
        $books = $this->getModel()->getAllBooks();
        return $this->render('index', compact('book'));
    }

    public function create()
    {
        return $this->render('create');
    }

    public function store(array $data)
    {
        $book = $this->getModel()->createBook($data);
        return $this->redirectToIndex($book);
    }

    public function show($id)
    {
        $book = $this->getModel()->getBookById($id);
        return $this->render('show', compact('book'));
    }

    public function edit($id)
    {
        $book = $this->getModel()->getBookById($id);
        return $this->render('edit', compact('book'));
    }

    public function update(array $data, $id)
    {
        $book = $this->getModel();
        $book->updateBook($data, $id);
        return $this->redirectToIndex($book);
    }

    public function destroy($id)
    {
        $book = $this->getModel();
        $book->deleteBook($id);
        return $this->redirectToIndex($book);
    }
}