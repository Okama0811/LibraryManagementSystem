<?php

namespace App\Views\Layouts;

class BaseLayout
{
    protected $data = [];

    public function with($data)
    {
        $this->data = is_array($data) ? $data : [];
        return $this;
    }

    public function render()
    {
        include 'views/layouts/base.php';
    }

    public function __get($key)
    {
        return $this->data[$key] ?? null;
    }
}