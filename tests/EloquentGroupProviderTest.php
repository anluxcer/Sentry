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
use Cartalyst\Sentry\Groups\Eloquent\Provider;
use GroupModelStub1;
use GroupModelStub2;
use PHPUnit_Framework_TestCase;

class EloquentGroupProviderTest extends PHPUnit_Framework_TestCase {

	/**
	 * Setup resources and dependencies.
	 *
	 * @return void
	 */
	public static function setUpBeforeClass()
	{
		require_once __DIR__.'/stubs/groups/GroupModelStubs.php';
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

	public function testFindingById()
	{
		$provider = m::mock('Cartalyst\Sentry\Groups\Eloquent\Provider[createModel]');

		$query = m::mock('StdClass');
		$query->shouldReceive('newQuery')->andReturn($query);
		$query->shouldReceive('find')->with(1)->once()->andReturn('foo');

		$provider->shouldReceive('createModel')->once()->andReturn($query);

		$this->assertEquals('foo', $provider->findById(1));
	}

	/**
	 * @expectedException Cartalyst\Sentry\Groups\GroupNotFoundException
	 */
	public function testFailedFindingByIdThrowsExceptionIfNotFound()
	{
		$provider = m::mock('Cartalyst\Sentry\Groups\Eloquent\Provider[createModel]');

		$query = m::mock('StdClass');
		$query->shouldReceive('newQuery')->andReturn($query);
		$query->shouldReceive('find')->with(1)->once()->andReturn(null);

		$provider->shouldReceive('createModel')->once()->andReturn($query);

		$provider->findById(1);
	}

	public function testFindingByName()
	{
		$provider = m::mock('Cartalyst\Sentry\Groups\Eloquent\Provider[createModel]');

		$query = m::mock('StdClass');
		$query->shouldReceive('newQuery')->andReturn($query);
		$query->shouldReceive('where')->with('name', '=', 'foo')->once()->andReturn($query);
		$query->shouldReceive('first')->once()->andReturn('bar');

		$provider->shouldReceive('createModel')->once()->andReturn($query);

		$this->assertEquals('bar', $provider->findByName('foo'));
	}

	/**
	 * @expectedException Cartalyst\Sentry\Groups\GroupNotFoundException
	 */
	public function testFailedFindingByNameThrowsExceptionIfNotFound()
	{
		$provider = m::mock('Cartalyst\Sentry\Groups\Eloquent\Provider[createModel]');

		$query = m::mock('StdClass');
		$query->shouldReceive('newQuery')->andReturn($query);
		$query->shouldReceive('where')->with('name', '=', 'foo')->once()->andReturn($query);
		$query->shouldReceive('first')->andReturn(null);

		$provider->shouldReceive('createModel')->once()->andReturn($query);

		$provider->findByName('foo');
	}

	public function testFindingAll()
	{
		$provider = m::mock('Cartalyst\Sentry\Groups\Eloquent\Provider[createModel]');

		$provider->shouldReceive('createModel')->once()->andReturn($query = m::mock('StdClass'));

		$group1 = m::mock('Cartalyst\Sentry\Groups\Eloquent\Group')->shouldReceive('hasGetMutator')->andReturn(false);
		$group2 = m::mock('Cartalyst\Sentry\Groups\Eloquent\Group')->shouldReceive('hasGetMutator')->andReturn(false);

		$query->shouldReceive('newQuery')->andReturn($query);
		$query->shouldReceive('get')->andReturn($query);
		$query->shouldReceive('all')->andReturn($groups = array($group1, $group2));

		$this->assertEquals($groups, $provider->findAll());
	}

	public function testCreatingGroup()
	{
		$attributes = array(
			'name' => 'foo',
		);

		$group = m::mock('Cartalyst\Sentry\Groups\EloquentGroup');
		$group->shouldReceive('fill')->with($attributes)->once();
		$group->shouldReceive('save')->once();

		$provider = m::mock('Cartalyst\Sentry\Groups\Eloquent\Provider[createModel]');
		$provider->shouldReceive('createModel')->once()->andReturn($group);

		$this->assertEquals($group, $provider->create($attributes));
	}

	public function testSettingModel()
	{
		$provider = new Provider('GroupModelStub1');

		$this->assertInstanceOf('GroupModelStub1', $provider->createModel());

		$provider->setModel('GroupModelStub2');
		$this->assertInstanceOf('GroupModelStub2', $provider->createModel());
	}

}
