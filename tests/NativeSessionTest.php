<?php

/*
 * This file is part of Sentry.
 *
 * (c) Cartalyst LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cartalyst\Sentry\Tests;

use Cartalyst\Sentry\Sessions\NativeSession;
use Mockery as m;
use PHPUnit_Framework_TestCase;
use stdClass;

class NativeSessionTest extends PHPUnit_Framework_TestCase {

	/**
	 * Close mockery.
	 *
	 * @return void
	 */
	public function tearDown()
	{
		m::close();
	}

	public function testFoo()
	{
		$session = $this->getMock('Cartalyst\Sentry\Sessions\NativeSession', array('startSession'));
	}

	public function testOverridingKey()
	{
		$session = $this->getMock('Cartalyst\Sentry\Sessions\NativeSession', array('startSession'), array('foo'));

		$this->assertEquals('foo', $session->getKey());
	}

	public function testPutting()
	{
		$session = $this->getMock('Cartalyst\Sentry\Sessions\NativeSession', array('startSession'), array('foo'));

		$class = new stdClass;
		$class->foo = 'bar';

		$session->put($class);
		$this->assertEquals(serialize($class), $_SESSION['foo']);
	}

	public function testGettingWhenNothingIsInSessionReturnsNull()
	{
		$session = $this->getMock('Cartalyst\Sentry\Sessions\NativeSession', array('startSession', 'getSession'));

		$this->assertNull($session->get());
	}

	public function testGetting()
	{
		$session = $this->getMock('Cartalyst\Sentry\Sessions\NativeSession', array('startSession'), array('foo'));

		$class = new stdClass;
		$class->foo = 'bar';
		$_SESSION['foo'] = serialize($class);

		$this->assertEquals($class, $session->get());
	}

	public function testForgetting()
	{
		$_SESSION['foo'] = 'bar';

		$session = $this->getMock('Cartalyst\Sentry\Sessions\NativeSession', array('startSession'), array('foo'));

		$this->assertEquals('bar', $_SESSION['foo']);
		$session->forget();
		$this->assertFalse(isset($_SESSION['foo']));
	}

}
