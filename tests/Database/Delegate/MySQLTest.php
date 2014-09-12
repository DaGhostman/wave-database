<?php
/**
 * Created by PhpStorm.
 * User: daghostman
 * Date: 11/09/14
 * Time: 22:58
 */

namespace Delegate;

use \Wave\Framework\Database\Delegate\MySQL;

class MySQLTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \Wave\Framework\Database\Delegate\MySQL
     */
    protected $db = null;

    protected function setUp()
    {
        $query = '';
        $mock = \Mockery::mock('Wave\Framework\Database\Database');
        $mock->shouldReceive('query')->withAnyArgs()->andReturnUsing(function ($q) use (&$query, $mock) {
            $query = $q;

            return $mock;
        });
        $mock->shouldReceive('exec')->withAnyArgs()->andReturnSelf();
        $mock->shouldReceive('execute')->withAnyArgs()->andReturnSelf();
        $mock->shouldReceive('getQuery')->withAnyArgs()->andReturnUsing(function () use (&$query) {
            return $query;
        });
        $mock->shouldReceive('__toString')->withNoArgs()->andReturnUsing(function () use (&$query) {
            return $query;
        });

        $this->db = new MySQL($mock);

    }
    protected function tearDown()
    {
        \Mockery::close();
    }

    public function testInsertQuery()
    {
        $this->db->insert('test', array('id', 'name'));
        $this->assertSame(
            'INSERT LOW_PRIORITY INTO test (id, name) VALUES (:id, :name)',
            (string) $this->db
        );
    }

    public function testLimit()
    {
        $this->db->limit(1, 2);
        $this->assertSame(
            ' LIMIT 2, 1',
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
}
