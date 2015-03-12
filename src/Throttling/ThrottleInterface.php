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

interface ThrottleInterface
{
    /**
     * Returns the associated user with the throttler.
     *
     * @return \Cartalyst\Sentry\Users\UserInterface
     */
    public function getUser();

    /**
     * Get the current amount of attempts.
     *
     * @return int
     */
    public function getLoginAttempts();

    /**
     * Add a new login attempt.
     *
     * @return void
     */
    public function addLoginAttempt();

    /**
     * Clear all login attempts.
     *
     * @return void
     */
    public function clearLoginAttempts();

    /**
     * Suspend the user associated with the throttle.
     *
     * @return void
     */
    public function suspend();

    /**
     * Unsuspend the user.
     *
     * @return void
     */
    public function unsuspend();

    /**
     * Check if the user is suspended.
     *
     * @return bool
     */
    public function isSuspended();

    /**
     * Ban the user.
     *
     * @return bool
     */
    public function ban();

    /**
     * Unban the user.
     *
     * @return void
     */
    public function unban();

    /**
     * Check if user is banned.
     *
     * @return void
     */
    public function isBanned();

    /**
     * Check user throttle status.
     *
     * @throws \Cartalyst\Sentry\Throttling\UserBannedException
     * @throws \Cartalyst\Sentry\Throttling\UserSuspendedException
     *
     * @return bool
     */
    public function check();

    /**
     * Saves the throttle.
     *
     * @return bool
     */
    public function save();
}
