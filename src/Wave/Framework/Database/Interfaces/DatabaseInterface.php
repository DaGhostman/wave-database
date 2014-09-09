<?php
/**
 * Created by PhpStorm.
 * User: daghostman
 * Date: 09/09/14
 * Time: 22:18
 */
namespace Wave\Framework\Database\Interfaces;

interface DatabaseInterface
{
    /**
     * @param array $params
     * @param array $options
     *
     * @return $this
     */
    public function exec ($params = array(), $options = array());

    /**
     * @return string
     */
    public function getQuery ();

    /**
     * @param string $sql
     * @param string $tableName
     *
     * @return $this
     */
    public function query ($sql, $tableName = null);

    /**
     * @param array $params
     * @param array $options
     *
     * @return $this
     */
    public function execute ($params = array(), $options = array());

    /**
     * @return \Wave\Framework\Database\Component\Table
     */
    public function fetchAll ();

    /**
     * @return \Wave\Framework\Database\Component\Row
     */
    public function fetch ();
}
