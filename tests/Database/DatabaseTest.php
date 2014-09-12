<?php
/**
 * Created by PhpStorm.
 * User: daghostman
 * Date: 08/09/14
 * Time: 20:10
 */

namespace Tests;

use Wave\Framework\Database\Database;

class DatabaseTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Database
     */
    private $db = null;

    protected function setUp()
    {
        $this->db = new Database(new \PDO('sqlite::memory:'));
    }

    protected function tearDown()
    {
        unset($this->db);
    }

    public function testDatabaseQuery()
    {
        $this->assertInstanceOf('\Wave\Framework\Database\Database', $this->db->query('SELECT 1+1;'));
        $this->assertSame($this->db, $this->db->query('SELECT 1+1'));
    }

    public function testStringCast()
    {
        $this->db->query("SELECT 1+1");

        $this->assertSame('SELECT 1+1', (string) $this->db);
    }

    public function testGetQuery()
    {
        $this->db->query('SELECT 1+1');

        $this->assertSame('SELECT 1+1', $this->db->getQuery());
    }

    public function testExec()
    {
        $this->assertInstanceOf(
            '\Wave\Framework\Database\Database',
            $this->db->query("SELECT 1+1")->exec()
        );
    }

    public function testSingleFetch()
    {
        $result = $this->db->query("SELECT 1+1")
            ->exec()
            ->fetch();

        $this->assertInstanceOf('\Wave\Framework\Database\Component\Row', $result);
    }

    public function testFetchAll()
    {
        $result = $this->db
            ->query("SELECT 1+1")
            ->exec()
            ->fetchAll();

        $this->assertInstanceOf('\Wave\Framework\Database\Component\Table', $result);
    }

    public function testNestedQuery()
    {
        $this->db->query('SELECT id FROM salary WHERE id > 100');
        $this->db->query(sprintf('SELECT id, name FROM employees WHERE id IN (%s)', $this->db->getQuery()));

        $this->assertSame(
            'SELECT id, name FROM employees WHERE id IN (SELECT id FROM salary WHERE id > 100)',
            $this->db->getQuery()
        );
    }
}
