<?php

/**
 * Created by PhpStorm.
 * User: rus
 * Date: 12.08.16
 * Time: 20:28
 * @property  from
 */
class queryBuilder
{

    private $select = '*';
    private $where;
    private $from;
    private $groupBy;
    private $sql;

    static private $conn;

    public function __construct()
    {

        self::$conn = mysqli_connect(dbconfig::HOST, dbconfig::LOGIN, dbconfig::PASSWORD, dbconfig::DATABASE);
    }

    public function Fields( array $fields)
    {
        $comma = '';
        $this->select = '';

        foreach ($fields as $key => $value) {
            $this->select .= "$comma $value as $key";
            $comma = ',';
        }
    }

    public function Where(array $condition)
    {
        $this->where = $separator = '';

        foreach ($condition as $key => $value) {
            if (is_array($value) ) {
                foreach ($value as $key => $arrow) {
                    switch ($key) {
                        case 'from':
                            $this->where .= "$separator $key < $value";
                        case 'to':
                            $this->where .= "$separator $key > $value";
                        case 'in':
                            $this->where .= "$separator in ($value)";
                    }
                }
            }
            else
                $this->where .= "$separator $key = $value";

            $separator = ' AND ';
        }

    }

    public function runPreparedSQL()
    {
        if (!$this->from) {
            throw new Exception('Not from!');
        }
        $sql = "select {$this->select} from {$this->from} " . ($this->where ? " where {$this->where}" : '') . ($this->groupBy ? " group by {$this->groupBy}" : '')  ;

        $this->setSql($sql);

        return mysqli_query( self::$conn, $sql );

    }

    /**
     * @param mixed $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @param mixed $groupBy
     */
    public function setGroupBy($groupBy)
    {
        $this->groupBy = $groupBy;
    }

    /**
     * @return mixed
     */
    public function getSql()
    {
        return $this->sql;
    }

    /**
     * @param mixed $sql
     */
    public function setSql($sql)
    {
        $this->sql = $sql;
    }

}