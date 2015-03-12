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

use Mockery as m;
use Cartalyst\Sentry\Sessions\IlluminateSession;
use PHPUnit_Framework_TestCase;

class IlluminateSessionTest extends PHPUnit_Framework_TestCase {

	protected $store;

	protected $session;

	/**
	 * Setup resources and dependencies.
	 *
	 * @return void
	 */
	public function setUp()
	{
		$this->store = m::mock('Illuminate\Session\Store');
		$this->session = new IlluminateSession($this->store, 'session_name_here');
	}

	/**
	 * Close mockery.
	 *
	 * @return void
	 */
	public function tearDown()
	{
		m::close();
	}

	public function testOverridingKey()
	{
		$this->session = new IlluminateSession($this->store, 'foo');
		$this->assertEquals('foo', $this->session->getKey());
	}

	public function testPut()
	{
		$this->store->shouldReceive('put')->with('session_name_here', 'bar')->once();

		$this->session->put('bar');
	}

	public function testGet()
	{
		$this->store->shouldReceive('get')->with('session_name_here')->once()->andReturn('bar');

		// Test with default "null" param as well
		$this->assertEquals('bar', $this->session->get());
	}

	public function testForget()
	{
		$this->store->shouldReceive('forget')->with('session_name_here')->once();

		$this->session->forget();
	}

}
