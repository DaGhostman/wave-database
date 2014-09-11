<?php
/**
 * Created by PhpStorm.
 * User: daghostman
 * Date: 11/09/14
 * Time: 23:07
 */

namespace Delegate;


use Wave\Framework\Database\Delegate\PGSql;

class PGSqlTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \Wave\Framework\Database\Delegate\PGSql
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

        $this->db = new PGSql($mock);

    }
    protected function tearDown()
    {
        \Mockery::close();
    }

    public function testInsert()
    {
        $this->db->insert('test', array('id', 'name'));
        $this->assertSame(
            'INSERT INTO test (id, name) VALUES (:id, :name)',
            (string) $this->db
        );
    }

    public function testBeginTransaction()
    {
        $this->db->beginTransaction();

        $this->assertSame(
            'START TRANSACTION',
            (string) $this->db
        );
    }
}
