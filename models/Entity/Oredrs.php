<?php

/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 02.09.2016
 * Time: 11:35
 */
class Oredrs
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
     * @return array|null
     */
    static public function getIDs()
    {
        $sql = "select id_order from orders ";
        $result = self::runSQL($sql);

        $arrResult = [];

        while( $row = mysqli_fetch_array($result))
        {
            $arrResult[] = $row[0];
        }

        return $arrResult;

    }

    /**
     * отбор по цене_заказа в зависимости от $param (по умолчанию по возрастанию, или DESC по убыванию)
     * @param $param
     * @return bool|mysqli_result
     */
    public static function orderByPrice($param = ''){
        $sql = "SELECT * FROM orders ORDER BY order_price {$param}";

        return self::runSQL($sql);
    }

    /**
     * Получение инфморации о последних 100 заказах
     * @return bool|mysqli_result
     */
    public static function lastOrders(){
        $sql = "SELECT * FROM orders ORDER BY order_date LIMIT 100";

        return self::runSQL($sql);
    }
    
    
    /**
     * получить общее кол-во заказов
     * @return bool|mysqli_result
     */
    static public function CountRecord()
    {
        $sql = 'SELECT COUNT(*) FROM orders';

        return self::runSQL($sql);

    }

    /**
     * получить все записи о всех товарах
     * @return bool|mysqli_result
     */
    static public function SelectAll()
    {
        $sql = 'SELECT * FROM orders';

        return self::runSQL($sql);
    }

    /**
     * отбор записей по условиям в массиве $arrSearch
     *  fromBY( [ 'customer_id' => '1000', 'order_price' => [ 'ot' => '1000', 'do' => '5000' ] ])
     * @param array $arrSearch
     * @return bool|mysqli_result
     */
    static function fromBy(array $arrSearch)
    {
        $sql = "SELECT * FROM orders WHERE ";
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
     * выбор записи по id
     * @param $id
     * @return bool|mysqli_result
     */
    public static function fromID($id){
        $sql = "SELECT * FROM orders WHERE id = '{$id}'";

        return self::runSQL($sql);
    }
}