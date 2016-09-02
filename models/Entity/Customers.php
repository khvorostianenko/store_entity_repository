<?php

/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 02.09.2016
 * Time: 11:19
 */
class Customers
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
        $sql = "select id_customer from customers ";
        $result = self::runSQL($sql);

        $arrResult = [];

        while( $row = mysqli_fetch_array($result))
        {
            $arrResult[] = $row[0];
        }

        return $arrResult;

    }
    
    /**
     * получение данных по $name
     * @param $name
     * @return bool|mysqli_result
     */
    public static function fromName($name){
        $sql = "SELECT * FROM customers WHERE name = '{$name}'";

        return self::runSQL($sql);
    }

    /**
     * получение всех покупателей по городу $city
     * @param $city
     * @return bool|mysqli_result
     */
    public static function fromCity($city){
        $sql = "SELECT * FROM customers WHERE city = '{$city}'";

        return self::runSQL($sql);
    }

    /**
     * покупатели с наибольшим или с наименьшим кол-вом заказов
     * @param string $param
     * @param string $limit
     * @return bool|mysqli_result
     */
    public static function orderByOrderCount($param = '', $limit = ''){
        if($limit != ''){
            $limit = "LIMIT {$limit}";
        }
        $sql = "SELECT c.name, count(c.id) FROM customers c 
JOIN orders o ON c.id = o.id  GROUP BY c.id ORDER BY count(c.id) {$param} {$limit}";

        return self::runSQL($sql);
    }

    /**
     * Общее кол-во клиентов
     * @return bool|mysqli_result
     */
    static public function CountRecord()
    {
        $sql = 'SELECT COUNT(*) FROM customers';

        return self::runSQL($sql);

    }

    /**
     * Получить все данные о всех клиентах
     * @return bool|mysqli_result
     */
    static public function SelectAll()
    {
        $sql = 'SELECT * FROM customers ';

        return self::runSQL($sql);
    }

    /**
     * отбор записей по условиям в массиве $arrSearch
     * fromBY( [ 'name' => 'Michael', 'address' => [ 'ot' => 'Kharkiv', 'do' => 'Kyiv' ] ])
     * @param array $arrSearch
     * @return bool|mysqli_result
     */
    static function fromBy(array $arrSearch)
    {
        $sql = "SELECT * FROM customers WHERE ";
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
     * Получить данные по одному клиентв
     * @param $id
     * @return bool|mysqli_result
     */
    public static function fromID($id){
        $sql = "SELECT * FROM customers WHERE id = '{$id}'";

        return self::runSQL($sql);
    }
    

}