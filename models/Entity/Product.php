<?php

class Product
{
    static private $conn = null;

    static public function runSQL($sql)
    {
        if(self::$conn != null){
            $result = mysqli_query( self::$conn, $sql );
            return $result;
        }

        self::$conn = mysqli_connect(dbconfig::HOST, dbconfig::LOGIN, dbconfig::PASSWORD, dbconfig::DATABASE);
        $result = mysqli_query( self::$conn, $sql );

        return $result;
    }

    /**
     *  Получить все id
     * @return array|null
     */
    static public function getIDs()
    {
        $sql = "select id_product from products ";
        $result = self::runSQL($sql);

        $arrResult = [];

        while( $row = mysqli_fetch_array($result))
        {
            $arrResult[] = $row[0];
        }

        return $arrResult;

    }

    /**
     * получаем все продукты которые в наличии на складе
     * @return bool|mysqli_result
     */
    static public function SelectAvaliable()
    {
        $sql = 'SELECT * FROM products WHERE avaliability = 1';

        return self::runSQL($sql);

    }

    /**
     * получаем все продукты запасы которых необходимо восполнить
     * @return bool|mysqli_result
     */
    static public function SelectNotAvaliable()
    {
        $sql = 'SELECT * FROM products WHERE avaliability <> 1';

        return self::runSQL($sql);

    }

    /**
     * получить кол-во наименований товаров
     * @return bool|mysqli_result
     */
    static public function CountRecord()
    {
        $sql = 'SELECT COUNT(*) FROM products';

        return self::runSQL($sql);

    }



    /**
     * получить все товары
     * @return bool|mysqli_result
     */
    static public function SelectAll()
    {
        $sql = 'SELECT * FROM products ';

        return self::runSQL($sql);
    }

    /**
     * отбор записей по условиям в массиве $arrSearch
     *  fromBY( [ 'item' => 'Apple', 'price' => [ 'ot' => '1000', 'do' => '5000' ] ])
     * @param array $arrSearch
     * @return bool|mysqli_result
     */
    static function fromBy(array $arrSearch)
    {
        $sql = "SELECT * FROM products WHERE ";
        $separator = '';

        foreach ($arrSearch as $key => $value)
        {
            if(is_array($value))
            {
                foreach($value as $clef => $meaning)
                {
                    switch ($clef){
                        case 'ot':
                            $sql = "{$separator} $key > $meaning";
                            $separator = ' AND ';
                            break;
                        case 'do':
                            $sql = "{$separator} $key < $meaning";
                            break;
                    }
                }
            } else{
                $sql .= "{$separator} {$key} = {$value}";
                $separator = ' AND ';
            }
        }

        return self::runSQL($sql);

    }

    /**
     * отбор по цене в зависимости от $param (по умолчанию по возрастанию, или DESC по убыванию)
     * @param $param
     * @return bool|mysqli_result
     */
    public static function orderByPrice($param = ''){
        $sql = "SELECT * FROM products ORDER BY price {$param}";

        return self::runSQL($sql);
    }

    /**
     * выбор записи по id
     * @param $id
     * @return bool|mysqli_result
     */
    public static function fromID($id){
        $sql = "SELECT * FROM products WHERE id = '{$id}'";

        return self::runSQL($sql);
    }

}