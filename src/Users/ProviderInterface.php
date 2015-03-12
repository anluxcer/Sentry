<?php

/*
 * This file is part of Sentry.
 *
 * (c) Cartalyst LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cartalyst\Sentry\Users;

use Cartalyst\Sentry\Groups\GroupInterface;

interface ProviderInterface
{
    /**
     * Finds a user by the given user ID.
     *
     * @param mixed $id
     *
     * @throws \Cartalyst\Sentry\Users\UserNotFoundException
     *
     * @return \Cartalyst\Sentry\Users\UserInterface
     */
    public function findById($id);

    /**
     * Finds a user by the login value.
     *
     * @param string $login
     *
     * @throws \Cartalyst\Sentry\Users\UserNotFoundException
     *
     * @return \Cartalyst\Sentry\Users\UserInterface
     */
    public function findByLogin($login);

    /**
     * Finds a user by the given credentials.
     *
     * @param array $credentials
     *
     * @throws \Cartalyst\Sentry\Users\UserNotFoundException
     *
     * @return \Cartalyst\Sentry\Users\UserInterface
     */
    public function findByCredentials(array $credentials);

    /**
     * Finds a user by the given activation code.
     *
     * @param string $code
     *
     * @throws \Cartalyst\Sentry\Users\UserNotFoundException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     *
     * @return \Cartalyst\Sentry\Users\UserInterface
     */
    public function findByActivationCode($code);

    /**
     * Finds a user by the given reset password code.
     *
     * @param string $code
     *
     * @throws RuntimeException
     * @throws \Cartalyst\Sentry\Users\UserNotFoundException
     *
     * @return \Cartalyst\Sentry\Users\UserInterface
     */
    public function findByResetPasswordCode($code);

    /**
     * Returns an all users.
     *
     * @return array
     */
    public function findAll();

    /**
     * Returns all users who belong to
     * a group.
     *
     * @param \Cartalyst\Sentry\Groups\GroupInterface $group
     *
     * @return array
     */
    public function findAllInGroup(GroupInterface $group);

    /**
     * Returns all users with access to
     * a permission(s).
     *
     * @param string|array $permissions
     *
     * @return array
     */
    public function findAllWithAccess($permissions);

    /**
     * Returns all users with access to
     * any given permission(s).
     *
     * @param array $permissions
     *
     * @return array
     */
    public function findAllWithAnyAccess(array $permissions);

    /**
     * Creates a user.
     *
     * @param array $credentials
     *
     * @return \Cartalyst\Sentry\Users\UserInterface
     */
    public function create(array $credentials);

    /**
     * Returns an empty user object.
     *
     * @return \Cartalyst\Sentry\Users\UserInterface
     */
    public function getEmptyUser();
}
