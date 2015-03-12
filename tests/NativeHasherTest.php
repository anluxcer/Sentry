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
use Cartalyst\Sentry\Hashing\NativeHasher as Hasher;
use PHPUnit_Framework_TestCase;

class NativeHasherTest extends PHPUnit_Framework_TestCase {

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
	}

	public function testHashingIsAlwaysCorrect()
	{
		$hasher         = new Hasher;
		$password       = 'f00b@rB@zb@T';
		$hashedPassword = $hasher->hash($password);

		$this->assertTrue($hasher->checkhash($password, $hashedPassword));
		$this->assertFalse($hasher->checkhash($password.'$', $hashedPassword));
	}

}
