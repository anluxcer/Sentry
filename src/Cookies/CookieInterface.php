<?php

/*
 * This file is part of Sentry.
 *
 * (c) Cartalyst LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cartalyst\Sentry\Cookies;

interface CookieInterface
{
    /**
     * Returns the cookie key.
     *
     * @return string
     */
    public function getKey();

    /**
     * Put a value in the Sentry cookie.
     *
     * @param mixed $value
     * @param int   $minutes
     *
     * @return void
     */
    public function put($value, $minutes);

    /**
     * Put a value in the Sentry cookie forever.
     *
     * @param mixed $value
     *
     * @return void
     */
    public function forever($value);

    /**
     * Get the Sentry cookie value.
     *
     * @return mixed
     */
    public function get();

    /**
     * Remove the Sentry cookie.
     *
     * @return void
     */
    public function forget();
}
