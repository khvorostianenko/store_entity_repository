<?php

/**
 * Created by PhpStorm.
 * User: rus
 * Date: 01.09.16
 * Time: 19:28
 */
class categoryView
{
    // тип представления (строка)
    private $viewType;

    public function __construct($viewType)
    {
        $this->viewType = $viewType;
    }

    public function Render($key, array $value)
    {
        switch ($this->viewType) {
            case 'view':
                return "<div><a href='http://allservice.in.ua/test_task/online_store/category/id=$key'> {$value['name']} </a></div>";
            case 'v_menu':
                return "<li><a href='http://allservice.in.ua/test_task/online_store/category/id=$key'> {$value['name']} </a> </li>";
            case 'id':
                return "<div> {$value['name']} </div>";
            default:
                return "<div> Неопредел вівод данніх </div>";
        }
    }
}