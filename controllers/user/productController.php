<?php

/**
 * Created by PhpStorm.
 * User: rus
 * Date: 18.08.16
 * Time: 19:55
 */
class productController
{
    private $responce;
    private $repository;


    public function __construct($command)
    {
        $this->repository = new Repository('Product');
        switch ($command) {
            case 'action':
                $this->responce   = '';
                $this->repository->SelectAction();
                foreach($this->repository as $key => $value ) {
                    $this->responce   .= "<div><a href='product/id=$key'> {$value['name']} </a> {$value['price']}</div>";
                }
                break;
            case 'hits':
                $this->responce   = '';
                $this->repository->SelectAll();
                foreach($this->repository as $key => $value ) {
                    $this->responce   .= "<div><a href='product/id=$key'> {$value['name']} </a> {$value['price']}</div>";
                }
                break;
            case 'new':
                $this->responce   = '';
                $this->repository->SelectNew();
                foreach($this->repository as $key => $value ) {
                    $this->responce   .= "<div><a href='product/id=$key'> {$value['name']} </a> {$value['price']}</div>";
                }
                break;
        }
        $this->responce   = $this->repository->getList( ['name'] );
    }

    public function getResponce()
    {
        return $this->responce;
    }

}