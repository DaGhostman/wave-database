<?php
/**
 * Created by PhpStorm.
 * User: daghostman
 * Date: 07/09/14
 * Time: 23:59
 */

namespace Wave\Framework\Database\Component;


use Wave\Framework\Database\Delegate\StandardDelegate;

class Table implements \Iterator, \Countable
{

    protected $name = null;

    /**
     * @var \Wave\Framework\Database\Delegate\StandardDelegate
     */
    protected $link = null;

    protected $rows = array();

    private $position = 0;


    public function __construct($rows, $name = null, $link = null)
    {
        if (!empty($name)) {
            $this->name = $name;
        }

        if (!empty($link) && !$link instanceof StandardDelegate) {
            $this->link = $link;
        }

        if (is_array($rows)) {
            $this->rows = $rows;
        }
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

    public function update()
    {
        if (is_null($this->link)) {
            throw new \LogicException('Unable to save. No connection link specified');
        }

        if (is_null($this->name)) {
            throw new \LogicException('No table name specified');
        }

        foreach ($this->rows as $row) {
            $this->link->update($this->name, array_keys($row))
                ->exec($row);
        }
    }

    public function push()
    {

        if (is_null($this->link)) {
            throw new \LogicException('Unable to save. No connection link specified');
        }

        if (is_null($this->name)) {
            throw new \LogicException('No table name specified');
        }

        foreach ($this->rows as $row) {
            $this->link->insert($this->name, array_keys($row))
                ->exec($row);
        }
    }
}
