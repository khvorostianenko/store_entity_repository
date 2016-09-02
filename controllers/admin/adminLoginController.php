<?php

/**
 * Created by PhpStorm.
 * User: rus
 * Date: 05.08.16
 * Time: 19:20
 */
class adminLoginController
{

    private $responce;

    public function __construct(array $parameters)
    {
        switch ($parameters[0]) {
            case 'getform':  // path {site}/admin/signin/?getform
                $this->responce = $this->getFormAction();
                break;
            default:
                $this->responce = $this->signinAction();
        }

    }

    public function getFormAction() {

        $form = new adminLoginFormClass();

        return $form->Render();

    }

    public function signinAction() {

        if (!isset($_POST['login'], $_POST['password']))
            throw new Exception('Not correct parameters for loginning');

        $authorization = new AuthorizationClass($_POST['login'], $_POST['password']);

        return $authorization->checkLogin();

    }
    public function getResponce()
    {
        return $this->responce;
    }
}