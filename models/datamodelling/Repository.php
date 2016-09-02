<?php

/**
 * Created by PhpStorm.
 * User: rus
 * Date: 12.08.16
 * Time: 19:11
 */
class repository implements Iterator, ArrayAccess
{
    // имя таблицы, с которой работаем (либо, представления)
    private $name;
    // количество записей
    private $countRecords = -1;
    // здесь будем содержать результат запроса
    private $tableRes;
    // для одной записи
    private $row = null;
    private $rowArrayAccess;
    private $arrIDs = [];
    private $counter = 0;

    /**
     * repository constructor.
     * @param $name Entity (Category, Product)
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->countRecords = $name::CountRecord();

        $this->arrIDs = $name::getIDs();
    }

    /**
     * получит запись по ее $id
     * $name Category
     * @param $id
     */
    public function fromID($id)
    {
        $name = $this->name;
        $this->tableRes = $name::fromID($id);
//        $this->getRecords("fromID($id)");
    }

    /**
     * получит записи всей таблицы
     */
    public function SelectAll()
    {
        $this->tableRes = $this->getRecords('SelectAll');
    }

    /**
     * получение акционных товаров
     */
    public function SelectAction()
    {
        $this->tableRes = $this->getRecords('SelectAction');
    }

    /**
     * получение новинок
     */
    public function SelectNew()
    {
        $this->tableRes = $this->getRecords('SelectNew');
    }

    /**
     *  получить предствление набора данных типа Лист
     * @param array $names
     * @return string
     */
    public function getList(array $names)
    {
        $text = '';

        while ($row=mysqli_fetch_assoc($this->tableRes)) {

            foreach($row as $key => $value)
            {
                if (in_array($key, $names)) {
                    $text .= $value . '<br>';
                }

            }
        }

        return $text;
    }

    /**
     * получение записей
     * метод не проверяет наличие метода, с помощью которого извлекают записи
     * @param $method
     * @return bool|mysqli_result
     */
    private function getRecords($method)
    {
        $name = $this->name;
        return $name::$method();
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    public function __sleep()
    {
        // TODO: Implement __sleep() method.

        return [ 'name', 'countRecords' ];
    }

    /**
     * @return int
     */
    public function getCountRecords()
    {
        return $this->countRecords;
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        // TODO: Implement current() method.
        return $this->row;
    }


    public function next()
    {
        // TODO: Implement next() method.
        if($this->row = mysqli_fetch_assoc($this->tableRes)){
            $this->counter++;
        }
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        // TODO: Implement key() method.
        return $this->counter;
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        // TODO: Implement valid() method.
        return $this->row ? true : false;
    }


    /**
     * @return bool
     */
    public function rewind()
    {
        // TODO: Implement rewind() method.
        $this->counter = 0;
        return mysqli_data_seek($this->tableRes, 0);
    }

    /**
     * @return null
     */
    public function getRow()
    {
        return $this->row;
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
        return isset( $this->arrIDs[$offset] );
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        // TODO: Implement offsetGet() method.
        $name = $this->name;

        $result = mysqli_fetch_assoc( $name::fromId( $this->arrIDs[$offset] ) );

        return $result;
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.

    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.

        unset( $this->arrIDs[$offset] );

    }
}