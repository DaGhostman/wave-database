<?php
/**
 * Created by PhpStorm.
 * User: daghostman
 * Date: 09/09/14
 * Time: 22:09
 */

namespace Wave\Framework\Database\Delegate;


use Wave\Framework\Database\Interfaces\DatabaseInterface;

abstract class stdDelegate {

    protected $database = null;
    protected $inTransaction = false;

    public function __construct($database)
    {
        if (!$database instanceof DatabaseInterface) {
            throw new \InvalidArgumentException("Invalid database object supplied");
        }

        $this->database = $database;
    }

    public function select($table, $fields)
    {
        $this->database->query(
            sprintf("SELECT %s FROM %s", implode(', ', $fields), $table),
            $table
        );

        return $this;
    }

    public function update($table, $fields) {
        $binds = array();
        foreach ($fields as $field) {
            array_push($binds, sprintf("%s = :%s", $field, $field));
        }

        $this->database->query(sprintf("UPDATE %s SET %s", $table, implode(', ', $binds)), $table);

        return $this;
    }

    public function delete($table) {
        $this->database->query(sprintf("DELETE FROM %s", $table), $table);

        return $this;
    }

    public function where($clause) {
        $this->database->query(
            sprintf("%s WHERE %s", $this->database->getQuery(), $clause)
        );

        return $this;
    }

    /**
     * @param int $limit
     * @param int $offset
     *
     * @return $this
     */
    public function limit($limit, $offset = 0)
    {
        $this->database->query(sprintf(
            "%s LIMIT %d OFFSET %d",
            $this->database->getQuery(),
            $limit,
            $offset
        ));

        return $this;
    }

    public function order($by, $order) {
        $this->database->query(
            sprintf("%s ORDER BY %s %s", $this->database->getQuery(), $by, $order)
        );

        return $this;
    }

    public function execute($params = array())
    {
        $this->database->execute($params);

        return $this;
    }

    public function fetch()
    {
        return $this->database->fetch();
    }

    public function fetchAll()
    {
        return $this->database->fetchAll();
    }

    public function __destruct()
    {
        if ($this->inTransaction) {
            $this->commit();
        }
    }

    public function commit()
    {
        $this->database->query('COMMIT')->exec();

        return $this;
    }

    public function rollback()
    {
        $this->database->query('ROLLBACK')->exec();

        return $this;
    }

    public function inTransaction()
    {
        return $this->inTransaction;
    }

    public function exec($params = array())
    {
        $this->database->exec($params);

        return $this;
    }

    abstract public function beginTransaction();

    abstract public function insert($table, $field);


} 
