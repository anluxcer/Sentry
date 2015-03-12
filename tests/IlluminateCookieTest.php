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
use Cartalyst\Sentry\Cookies\IlluminateCookie;
use PHPUnit_Framework_TestCase;

class IlluminateCookieTest extends PHPUnit_Framework_TestCase {

	protected $request;

	protected $jar;

	protected $cookie;

	/**
	 * Setup resources and dependencies.
	 *
	 * @return void
	 */
	public function setUp()
	{
		$this->request = m::mock('Illuminate\Http\Request');
		$this->jar = m::mock('Illuminate\Cookie\CookieJar');

		$this->cookie = new IlluminateCookie($this->request, $this->jar, 'cookie_name_here');
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

	public function testPut()
	{
		$this->jar->shouldReceive('make')->with('cookie_name_here', 'bar', 123)->once()->andReturn('cookie');
		$this->jar->shouldReceive('queue')->with('cookie')->once();
		$this->cookie->put('bar', 123);
	}

	public function testForever()
	{
		$this->jar->shouldReceive('forever')->with('cookie_name_here', 'bar')->once()->andReturn('cookie');
		$this->jar->shouldReceive('queue')->with('cookie')->once();
		$this->cookie->forever('bar');
	}

	public function testGetWithQueuedCookie()
	{
		$this->jar->shouldReceive('getQueuedCookies')->once()->andReturn(array('cookie_name_here' => 'bar'));
		// $this->request->shouldReceive('cookie')->with('cookie_name_here')->once()->andReturn('bar');

		// Ensure default param is "null"
		$this->assertEquals('bar', $this->cookie->get());
	}

	public function testGetWithPreviousCookies()
	{
		$this->jar->shouldReceive('getQueuedCookies')->once()->andReturn(array());
		$this->request->shouldReceive('cookie')->with('cookie_name_here')->once()->andReturn('bar');

		// Ensure default param is "null"
		$this->assertEquals('bar', $this->cookie->get());
	}

	public function testGetWithJarStrategy()
	{
		$cookie = new IlluminateCookie($this->request, $this->jar, 'cookie_name_here', 'jar');

		$this->jar->shouldReceive('getQueuedCookies')->once()->andReturn(array());
		$this->jar->shouldReceive('get')->with('cookie_name_here')->once()->andReturn('bar');

		$this->assertEquals('bar', $cookie->get());
	}

	public function testForget()
	{
		$this->jar->shouldReceive('forget')->with('cookie_name_here')->once()->andReturn('cookie');
		$this->jar->shouldReceive('queue')->with('cookie')->once();
		$this->cookie->forget();
	}

}
