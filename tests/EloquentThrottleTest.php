<?php

/*
 * This file is part of Sentry.
 *
 * (c) Cartalyst LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cartalyst\Sentry\tests;

use Cartalyst\Sentry\Throttling\Eloquent\Throttle;
use DateTime;
use Mockery as m;
use PHPUnit_Framework_TestCase;

class EloquentThrottleTest extends PHPUnit_Framework_TestCase
{
    /**
     * Setup resources and dependencies.
     *
     * @return void
     */
    public function setUp()
    {
    }

    /**
     * Close mockery.
     *
     * @return void
     */
    public function tearDown()
    {
        m::close();
        Throttle::setAttemptLimit(5);
        Throttle::setSuspensionTime(15);
    }

    public function testGettingUserReturnsUserObject()
    {
        $user = m::mock('StdClass');
        $user->shouldReceive('getResults')->once()->andReturn('foo');

        $throttle = m::mock('Cartalyst\Sentry\Throttling\Eloquent\Throttle[user]');
        $throttle->shouldReceive('user')->once()->andReturn($user);

        $this->assertEquals('foo', $throttle->getUser());
    }

    public function testAttemptLimits()
    {
        Throttle::setAttemptLimit(15);
        $this->assertEquals(15, Throttle::getAttemptLimit());
    }

    public function testGettingLoginAttemptsWhenNoAttemptHasBeenMadeBefore()
    {
        $throttle = m::mock('Cartalyst\Sentry\Throttling\Eloquent\Throttle[clearLoginAttemptsIfAllowed]');
        $throttle->shouldReceive('clearLoginAttemptsIfAllowed')->never();

        $this->assertEquals(0, $throttle->getLoginAttempts());
        $throttle->attempts = 1;
        $this->assertEquals(1, $throttle->getLoginAttempts());
    }

    public function testGettingLoginAttemptsResetsIfSuspensionTimeHasPassedSinceLastAttempt()
    {
        $throttle = m::mock('Cartalyst\Sentry\Throttling\Eloquent\Throttle[save]');
        $this->addMockConnection($throttle);
        $throttle->getConnection()->getQueryGrammar()->shouldReceive('getDateFormat')->andReturn('Y-m-d H:i:s');

        // Let's simulate that the suspension time
        // is 11 minutes however the last attempt was
        // 10 minutes ago, we'll not reset the attempts
        Throttle::setSuspensionTime(11);
        $lastAttemptAt = new DateTime();
        $lastAttemptAt->modify('-10 minutes');

        $throttle->last_attempt_at = $lastAttemptAt->format('Y-m-d H:i:s');
        $throttle->attempts = 3;
        $this->assertEquals(3, $throttle->getLoginAttempts());

        // Suspension time is 9 minutes now,
        // our attempts shall be reset
        $throttle->shouldReceive('save')->once();
        Throttle::setSuspensionTime(9);
        $this->assertEquals(0, $throttle->getLoginAttempts());
    }

    public function testSuspend()
    {
        $connection = m::mock('StdClass');
        $connection->shouldReceive('getQueryGrammar')->atLeast(1)->andReturn($connection);
        $connection->shouldReceive('getDateFormat')->atLeast(1)->andReturn('Y-m-d H:i:s');

        $throttle = m::mock('Cartalyst\Sentry\Throttling\Eloquent\Throttle[save,getConnection]');
        $throttle->shouldReceive('getConnection')->atLeast(1)->andReturn($connection);
        $throttle->shouldReceive('save')->once();

        $this->assertNull($throttle->suspended_at);
        $throttle->suspend();

        $this->assertNotNull($throttle->suspended_at);
        $this->assertTrue($throttle->suspended);
    }

    public function testUnsuspend()
    {
        $connection = m::mock('StdClass');
        $connection->shouldReceive('getQueryGrammar')->atLeast(1)->andReturn($connection);
        $connection->shouldReceive('getDateFormat')->atLeast(1)->andReturn('Y-m-d H:i:s');

        $throttle = m::mock('Cartalyst\Sentry\Throttling\Eloquent\Throttle[save,getConnection]');
        $throttle->shouldReceive('getConnection')->atLeast(1)->andReturn($connection);

        $throttle->shouldReceive('save')->once();

        $lastAttemptAt = new DateTime();
        $suspendedAt = new DateTime();

        $throttle->attempts = 3;
        $throttle->last_attempt_at = $lastAttemptAt;
        $throttle->suspended = true;
        $throttle->suspended_at = $suspendedAt;

        $throttle->unsuspend();

        $this->assertEquals(0, $throttle->attempts);
        $this->assertNull($throttle->last_attempt_at);
        $this->assertFalse($throttle->suspended);
        $this->assertNull($throttle->suspended_at);
    }

    protected function addMockConnection($model)
    {
        $model->setConnectionResolver($resolver = m::mock('Illuminate\Database\ConnectionResolverInterface'));
        $resolver->shouldReceive('connection')->andReturn(m::mock('Illuminate\Database\Connection'));
        $model->getConnection()->shouldReceive('getQueryGrammar')->andReturn(m::mock('Illuminate\Database\Query\Grammars\Grammar'));
        $model->getConnection()->shouldReceive('getPostProcessor')->andReturn(m::mock('Illuminate\Database\Query\Processors\Processor'));
    }
}
