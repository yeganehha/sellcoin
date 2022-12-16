<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Route;

abstract class DataTableComponent extends \Rappasoft\LaravelLivewireTables\DataTableComponent
{
    public $title = "Default Title";
    public $addRoute = null;
    public $backRoute = null;

    public function render()
    {
        if( $this->title == "Default Title") {
            $class = explode('\\' , $this->model);
            $this->title = ucfirst(strtolower(end($class)));
        }
        if ( $this->addRoute  ) {
            if ( is_array($this->addRoute )){
                $route = $this->addRoute[0];
                array_shift($this->addRoute);
                $this->addRoute = route($route , $this->addRoute);
            } else
                if ( Route::has($this->addRoute))
                    $this->addRoute = route($this->addRoute);
        }
        if ( $this->backRoute  ) {
            if ( is_array($this->backRoute )){
                $route = $this->backRoute[0];
                array_shift($this->backRoute);
                $this->backRoute = route($route , $this->backRoute);
            } else
                if ( Route::has($this->backRoute))
                    $this->backRoute = route($this->backRoute);
        }
        return parent::render()->layout('layouts.app');
    }


    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('id', 'desc');
    }
}
