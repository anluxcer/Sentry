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
use Cartalyst\Sentry\Groups\Eloquent\Group;
use PHPUnit_Framework_TestCase;

class EloquentGroupTest extends PHPUnit_Framework_TestCase {

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

	public function testGroupId()
	{
		$group = new Group;
		$group->id = 123;

		$this->assertEquals(123, $group->getId());
	}

	public function testGroupName()
	{
		$group = new Group;
		$group->name = 'foo';

		$this->assertEquals('foo', $group->getName());
	}

	// public function testSettingPermissions()
	// {
	// 	$permissions = array(
	// 		'foo' => 1,
	// 		'bar' => 1,
	// 		'baz' => 1,
	// 		'qux' => 1,
	// 	);

	// 	$group = new Group;

	// 	$expected = '{"foo":1,"bar":1,"baz":1,"qux":1}';

	// 	$this->assertEquals($expected, $group->setPermissions($permissions));
	// }

	// public function testSettingPermissionsWhenSomeAreSetTo0()
	// {
	// 	$permissions = array(
	// 		'foo' => 1,
	// 		'bar' => 1,
	// 		'baz' => 0,
	// 		'qux' => 1,
	// 	);

	// 	$group = new Group;

	// 	$expected = '{"foo":1,"bar":1,"qux":1}';

	// 	$this->assertEquals($expected, $group->setPermissions($permissions));
	// }

	public function testPermissionsAreMergedAndRemovedProperly()
	{
		$group = new Group;
		$group->permissions = array(
			'foo' => 1,
			'bar' => 1,
		);

		$group->permissions = array(
			'baz' => 1,
			'qux' => 1,
			'foo' => 0,
		);

		$expected = array(
			'bar' => 1,
			'baz' => 1,
			'qux' => 1,
		);

		$this->assertEquals($expected, $group->permissions);
	}

	public function testPermissionsAreCastAsAnArrayWhenTheModelIs()
	{
		$group = new Group;
		$group->name = 'foo';
		$group->permissions = array(
			'bar' => 1,
			'baz' => 1,
			'qux' => 1,
		);

		$expected = array(
			'name' => 'foo',
			'permissions' => array(
				'bar' => 1,
				'baz' => 1,
				'qux' => 1,
			),
		);

		$this->assertEquals($expected, $group->toArray());
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownForInvalidPermissionsDecoding()
	{
		$json = '{"foo":1,"bar:1';
		$group = new Group;

		$group->getPermissionsAttribute($json);
	}

	/**
	 * Regression test for https://github.com/cartalyst/sentry/issues/103
	 */
	public function testSettingPermissionsWhenPermissionsAreStrings()
	{
		$group = new Group;
		$group->permissions = array(
			'admin'    => '1',
			'foo'      => '0',
		);

		$expected = array(
			'admin'     => 1,
		);

		$this->assertEquals($expected, $group->permissions);
	}

	/**
	 * Regression test for https://github.com/cartalyst/sentry/issues/103
	 */
	public function testSettingPermissionsWhenAllPermissionsAreZero()
	{
		$group = new Group;

		$group->permissions = array(
			'admin'     => 0,
		);

		$this->assertEquals(array(), $group->permissions);
	}

	public function testValidation()
	{
		$group = m::mock('Cartalyst\Sentry\Groups\Eloquent\Group[newQuery]');
		$group->name = 'foo';

		$query = m::mock('StdClass');
		$query->shouldReceive('where')->with('name', '=', 'foo')->once()->andReturn($query);
		$query->shouldReceive('first')->once()->andReturn(null);

		$group->shouldReceive('newQuery')->once()->andReturn($query);

		$group->validate();
	}

	/**
	 * @expectedException Cartalyst\Sentry\Groups\NameRequiredException
	 */
	public function testValidationThrowsExceptionForMissingName()
	{
		$group = new Group;
		$group->validate();
	}

	/**
	 * @expectedException Cartalyst\Sentry\Groups\GroupExistsException
	 */
	public function testValidationThrowsExceptionForDuplicateNameOnNonExistent()
	{
		$persistedGroup = m::mock('Cartalyst\Sentry\Groups\GroupInterface');
		$persistedGroup->shouldReceive('getId')->once()->andReturn(123);

		$group = m::mock('Cartalyst\Sentry\Groups\Eloquent\Group[newQuery]');
		$group->name = 'foo';

		$query = m::mock('StdClass');
		$query->shouldReceive('where')->with('name', '=', 'foo')->once()->andReturn($query);
		$query->shouldReceive('first')->once()->andReturn($persistedGroup);

		$group->shouldReceive('newQuery')->once()->andReturn($query);

		$group->validate();
	}

	/**
	 * @expectedException Cartalyst\Sentry\Groups\GroupExistsException
	 */
	public function testValidationThrowsExceptionForDuplicateNameOnExistent()
	{
		$persistedGroup = m::mock('Cartalyst\Sentry\Groups\GroupInterface');
		$persistedGroup->shouldReceive('getId')->once()->andReturn(123);

		$group = m::mock('Cartalyst\Sentry\Groups\Eloquent\Group[newQuery]');
		$group->id   = 124;
		$group->name = 'foo';

		$query = m::mock('StdClass');
		$query->shouldReceive('where')->with('name', '=', 'foo')->once()->andReturn($query);
		$query->shouldReceive('first')->once()->andReturn($persistedGroup);

		$group->shouldReceive('newQuery')->once()->andReturn($query);

		$group->validate();
	}

	public function testValidationDoesNotThrowAnExceptionIfPersistedGroupIsThisGroup()
	{
		$persistedGroup = m::mock('Cartalyst\Sentry\Groups\GroupInterface');
		$persistedGroup->shouldReceive('getId')->once()->andReturn(123);

		$group = m::mock('Cartalyst\Sentry\Groups\Eloquent\Group[newQuery]');
		$group->id   = 123;
		$group->name = 'foo';

		$query = m::mock('StdClass');
		$query->shouldReceive('where')->with('name', '=', 'foo')->once()->andReturn($query);
		$query->shouldReceive('first')->once()->andReturn($persistedGroup);

		$group->shouldReceive('newQuery')->once()->andReturn($query);

		$group->validate();
	}

	public function testPermissionsWithArrayCastingAndJsonCasting()
	{
		$group = new Group;
		$group->name = 'foo';
		$group->permissions = array(
			'foo' => 1,
			'bar' => 0,
			'baz' => 1,
		);

		$expected = array(
			'name'        => 'foo',
			'permissions' => array(
				'foo' => 1,
				'baz' => 1,
			),
		);

		$this->assertEquals($expected, $group->toArray());

		$expected = json_encode($expected);
		$this->assertEquals($expected, (string) $group);
	}

	public function testDeletingGroupDetachesAllUserRelationships()
	{
		$relationship = m::mock('StdClass');
		$relationship->shouldReceive('detach')->once();

		$group = m::mock('Cartalyst\Sentry\Groups\Eloquent\Group[users]');
		$group->shouldReceive('users')->once()->andReturn($relationship);

		$group->delete();
	}

}
