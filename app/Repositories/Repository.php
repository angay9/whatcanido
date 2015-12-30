<?php namespace App\Repositories;

abstract class Repository {

    protected $model;

    public function __call($method, array $args)
    {
        return call_user_func_array([$this->model, $method], $args);
    }
}