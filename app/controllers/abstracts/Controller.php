<?php

namespace App\Http\Controllers\Abstracts;

use App\Models\Abstracts\Model;

abstract class Controller
{
    protected $model;

    public function index()
    {
        $models = $this->getModel()->all();
        return $this->render('index', compact('models'));
    }

    public function create()
    {
        return $this->render('create');
    }

    public function store() 
    {
        $data = $this->getPostData();
        $model = $this->getModel()->create($data);
        return $this->redirectToIndex($model);
    }

    public function show($id)
    {
        $model = $this->getModel()->find($id);
        return $this->render('show', compact('model'));
    }

    public function edit($id)
    {
        $model = $this->getModel()->find($id);
        return $this->render('edit', compact('model'));
    }

    public function update($id)
    {
        $data = $this->getPostData(); 
        $model = $this->getModel();
        $model->update($data, $id);
        return $this->redirectToIndex($model);
    }


    public function destroy($id)
    {
        $model = $this->getModel();
        $model->delete($id);
        return $this->redirectToIndex($model);
    }

    protected function getModel()
    {
        return $this->model;
    }

    protected function redirectToIndex($model)
    {
        header('Location: index.php');
        exit;
    }

    protected function render($view, $data = [])
    {
        extract($data);
        // Lấy tên class của model 
        $modelName = (new \ReflectionClass($this->getModel()))->getShortName(); 

        $viewPath = strtolower($modelName);
        include "views/{$viewPath}/{$view}.php";
    }

    protected function getPostData()
    {
        return $_POST;
    }
}