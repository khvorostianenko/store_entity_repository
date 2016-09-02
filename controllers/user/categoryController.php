<?php

/**
 * Created by PhpStorm.
 * User: rus
 * Date: 12.08.16
 * Time: 19:18
 * @property Repository repository
 */
class categoryController
{
    private $responce;
    private $repository;


    public function __construct($command)
    {
        $this->repository = new Repository('Category');

        $arrCommand = explode('=', $command );

        switch ($arrCommand[0]) {
            case 'parent':
                $this->repository->SelectAll();
                $this->responce   = $this->repository->getList( ['name'] );
                break;
            case 'child':
                $this->repository->SelectAll();
                $this->responce   = '';
                $view = new categoryView($arrCommand[1]);

                foreach($this->repository as $key => $value ) {
                        $this->responce .= $view->Render($key, $value);
                }
                break;
            case 'id':

                if (  !( (integer)$arrCommand[1] > 0) )  {
                    $this->responce   = "Значение номера категории должно быть положительным числом!"  . $arrCommand[1];
                    return;
                }

                $this->responce   = 'Показ одной записи №' . $arrCommand[1];

                $value = $this->repository[$arrCommand[1]];

                $this->repository[$arrCommand[1]] = 0;

                $view = new categoryView('id');

                $this->responce .= $view->Render( $arrCommand[1], $value);

                break;

            default:
                $this->responce = 'Default from Category';
        }
    }

    public function getResponce()
    {
        return $this->responce;
    }
}