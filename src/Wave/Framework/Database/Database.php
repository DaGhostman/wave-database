<?php
/**
 * Created by PhpStorm.
 * User: daghostman
 * Date: 07/09/14
 * Time: 23:51
 */

namespace Wave\Framework\Database;

use Wave\Framework\Database\Component\Row;
use Wave\Framework\Database\Component\Table;

use Traversable;
use Wave\Framework\Database\Interfaces\DatabaseInterface;

class Database implements DatabaseInterface
{
    /**
     * @var \PDO
     */
    protected $link;
    /**
     * @var \PDOStatement
     */
    protected $stmt = null;

    protected $table = 'Table';

    protected $query = '';


    public function __construct($connection)
    {
        $this->link = $connection;
        $this->link->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function query($sql, $tableName = null)
    {
        if (!is_null($tableName)) {
            $this->table = $tableName;
        }

        $this->query = $sql;

        return $this;
    }

    public function getQuery()
    {
        return $this->query;
    }



    public function execute($params = array(), $options = array())
    {
        $this->stmt = $this->link->prepare($this->query, $options);
        $this->stmt->execute($params);

        return $this;
    }
    public function exec($params = array(), $options = array())
    {
        return $this->execute($params, $options);
    }
    public function fetch()
    {
        return new Row($this->stmt->fetch());
    }
    public function fetchAll()
    {
        $rows = array();

        foreach ($this->stmt->fetchAll(\PDO::FETCH_BOTH) as $row) {
            array_push($rows, new Row($row));
        }

        return new Table($rows, 'id', $this->table);
    }

    public function __toString()
    {
        return $this->query;
    }
}
