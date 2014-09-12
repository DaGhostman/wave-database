<?php
/**
 * Created by PhpStorm.
 * User: daghostman
 * Date: 07/09/14
 * Time: 23:59
 */

namespace Wave\Framework\Database\Component;


class Table implements \Iterator, \Countable
{

    protected $name = null;
    protected $pkey = null;

    protected $rows = array();

    private $position = 0;


    public function __construct($name, $rows, $primaryKey = null)
    {
        $this->pkey = $primaryKey;

        $this->rows = $rows;


        if (!is_null($primaryKey)) {
            foreach ($rows as $row) {
                $this->rows[--$row->$primaryKey] = $row;
            }
        }
    }

    public function setPrimaryKey($key)
    {
        $this->pkey = $key;
    }

    public function getById($id)
    {
        if (is_null($this->pkey)) {
            throw new \Exception("Primary key not defined");
        }

        if (!isset($this->rows[--$id])) {
            throw new \OutOfBoundsException("Requested id is not in range");
        }

        return $this->rows[$id];
    }

    public function current()
    {
        return $this->rows[$this->position];
    }

    public function next()
    {
        $this->position++;
    }

    public function key()
    {
        return $this->position;
    }

    public function valid()
    {
        return isset($this->rows[$this->position]);
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function count()
    {
        return count($this->rows);
    }
}
