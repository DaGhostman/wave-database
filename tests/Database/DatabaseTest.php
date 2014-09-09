<?php
/**
 * Created by PhpStorm.
 * User: daghostman
 * Date: 08/09/14
 * Time: 20:10
 */

class DatabaseTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \Wave\Framework\Database\Database
     */
    private $db = null;

    protected function setUp()
    {
        $this->db = new \Wave\Framework\Database\Database(new \PDO('sqlite::memory:'));
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
}
