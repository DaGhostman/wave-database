<?php
/**
 * Created by PhpStorm.
 * User: daghostman
 * Date: 09/09/14
 * Time: 23:35
 */

namespace Delegate;


use Wave\Framework\Database\Delegate\SQLite;

class SQLiteTest extends \PHPUnit_Framework_TestCase {
    /**
     * @var \Wave\Framework\Database\Delegate\SQLite
     */
    private $db = null;

    protected function setUp()
    {
        $query = '';
        $mock = \Mockery::mock('Wave\Framework\Database\Database');
        $mock->shouldReceive('query')->withAnyArgs()->andReturnUsing(function($q) use (&$query, $mock) {
            $query = $q;

            return $mock;
        });
        $mock->shouldReceive('exec')->withAnyArgs()->andReturnSelf();
        $mock->shouldReceive('execute')->withAnyArgs()->andReturnSelf();
        $mock->shouldReceive('getQuery')->withAnyArgs()->andReturnUsing(function () use (&$query) {
            return $query;
        });
        $mock->shouldReceive('__toString')->withNoArgs()->andReturnUsing(function() use (&$query) {
            return $query;
        });

        $this->db = new SQLite($mock);

    }

    protected function tearDown()
    {
        \Mockery::close();
    }

    public function testDatabaseInit()
    {
        $db = $this->db;

        $this->assertInstanceOf(
            '\Wave\Framework\Database\Delegate\SQLite',
            $db->select('movie', array('movie_name'))
        );
    }

    public function testInsertQueryString()
    {
        $this->db->insert('test', array('id', 'name'));

        $this->assertSame(
            'INSERT OR FAIL INTO test (id, name) VALUES (:id, :name)',
            (string) $this->db
        );
    }

    public function testTransactionStart()
    {
        $this->db->beginTransaction();
        $this->assertSame(
            'BEGIN TRANSACTION',
            (string) $this->db
        );
    }

    public function testOrderQuery()
    {
        $this->db->order('id');

        $this->assertSame(' ORDER BY id', (string) $this->db);
    }


    public function testPlainSelectQuery()
    {
        $this->db->select('test', array('id', 'name'));
        $this->assertSame(
            'SELECT id, name FROM test',
            (string) $this->db
        );
    }

    public function testSelectWithLimit()
    {
        $this->db->select('test', array('id', 'name'))
            ->limit(3);

        $this->assertSame(
            'SELECT id, name FROM test LIMIT 3 OFFSET 0',
            (string) $this->db
        );
    }

    public function testSelectWithWhere()
    {
        $this->db->select('test', array('id', 'name'))
            ->where('id = 1');

        $this->assertSame(
            'SELECT id, name FROM test WHERE id = 1',
            (string) $this->db
        );
    }

}
