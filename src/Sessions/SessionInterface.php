<?php

/*
 * This file is part of Sentry.
 *
 * (c) Cartalyst LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cartalyst\Sentry\Sessions;

interface SessionInterface
{
    /**
     * Returns the session key.
     *
     * @return string
     */
    public function getKey();

    /**
     * Put a value in the Sentry session.
     *
     * @param mixed $value
     *
     * @return void
     */
    public function put($value);

    /**
     * Get the Sentry session value.
     *
     * @return mixed
     */
    public function get();

    /**
     * Remove the Sentry session.
     *
     * @return void
     */
    public function forget();
}
