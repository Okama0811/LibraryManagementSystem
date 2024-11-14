<?php

namespace App\Models;

use App\Models\Abstracts\Model;

class Book extends Model
{
    protected $table = 'books';

    public function __construct()
    {
        parent::__construct();
    }

    public function getAllBooks()
    {
        return $this->all();
    }

    public function getBookById($id)
    {
        return $this->find($id);
    }

    public function createBook(array $data)
    {
        return $this->create($data);
    }

    public function updateBook(array $data, $id)
    {
        return $this->update($data, $id);
    }

    public function deleteBook($id)
    {
        return $this->delete($id);
    }
}