<?php

/*
 * This file is part of Sentry.
 *
 * (c) Cartalyst LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cartalyst\Sentry\Throttling;

use Cartalyst\Sentry\Users\UserInterface;

interface ProviderInterface
{
    /**
     * Finds a throttler by the given user ID.
     *
     * @param \Cartalyst\Sentry\Users\UserInterface $user
     * @param string                                $ipAddress
     *
     * @return \Cartalyst\Sentry\Throttling\ThrottleInterface
     */
    public function findByUser(UserInterface $user, $ipAddress = null);

    /**
     * Finds a throttler by the given user ID.
     *
     * @param mixed  $id
     * @param string $ipAddress
     *
     * @return \Cartalyst\Sentry\Throttling\ThrottleInterface
     */
    public function findByUserId($id, $ipAddress = null);

    /**
     * Finds a throttling interface by the given user login.
     *
     * @param string $login
     * @param string $ipAddress
     *
     * @return \Cartalyst\Sentry\Throttling\ThrottleInterface
     */
    public function findByUserLogin($login, $ipAddress = null);

    /**
     * Enable throttling.
     *
     * @return void
     */
    public function enable();

    /**
     * Disable throttling.
     *
     * @return void
     */
    public function disable();

    /**
     * Check if throttling is enabled.
     *
     * @return bool
     */
    public function isEnabled();
}
