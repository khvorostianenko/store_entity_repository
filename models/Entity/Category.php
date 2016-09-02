<?php

/**
 * Created by PhpStorm.
 * User: rus
 * Date: 10.08.16
 * Time: 19:43
 */
class Category
{
    // primary key, autoincrement
    private $id;
    /**
     * @var string
     *
     * @ORM\Column(name="question", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;
    // integer, link by primary key parent category
    private $parentId;
    // handle соединения с БД
    static private $conn;

    public function __construct($name, $parentId = 0)
    {
        $this->name = $name;
        $this->parentId = $parentId;

    }

    /**
     * Определяем число записей в таблице
     * @return integer
     */
    static public function CountRecord()
    {
        $sql = 'select count(*) from category ';

        $result = self::runSQL($sql);

        $row = mysqli_fetch_assoc($result);

        return $row[0];

    }
    /**
     * выполнение запроса к БД через интерфейс mysqli
     * @param $sql
     * @return bool|mysqli_result
     */
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
     * Получить все id
     * @return array|null
     */
    static public function getIDs()
    {
        $sql = "select key_category from category ";
        $result = self::runSQL($sql);

        $arrResult = [];

        while( $row = mysqli_fetch_array($result))
        {
            $arrResult[] = $row[0];
        }

        return $arrResult;

    }
    /**
     * получение всех записей таблицы
     * @return bool|mysqli_result
     */
    static public function SelectAll()
    {
        $sql = "select key_category as 'id', name from category ";

        return self::runSQL($sql);

    }
    /**
     * получение одной записи таблицы по ее $id
     * @param $id integer
     * @return bool|mysqli_result
     */
    public function fromID($id)
    {
        $sql = "select  key_category as 'id', name from category where key_category = '$id'";

        return self::runSQL($sql);
    }

    /**
     * получение одной записи таблицы по ее полю $name
     * @param $name string
     * @return bool|mysqli_result
     */
    public function fromName($name)
    {
        $sql = "select * from category where name = $name";

        return self::runSQL($sql);
    }

    /**
     * отбор записей по условиям в массиве $arrSearch
     *  fromBY( [ 'name' => 'Dmitro', 'address' => [ 'ot' => 'Bruclin', 'do' => 'Moskau' ] ])
     * @param array $arrSearch
     * @return bool|mysqli_result
     */
    public function fromBy(array $arrSearch)
    {
        $sql = "SELECT * FROM category WHERE ";
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
     * @return int
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @param int $parentId
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}