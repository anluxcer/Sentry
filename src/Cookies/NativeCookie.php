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

class NativeCookie implements CookieInterface
{
    /**
     * The key used in the Cookie.
     *
     * @var string
     */
    protected $key = 'cartalyst_sentry';

    /**
     * Default settings.
     *
     * @var array
     */
    protected $defaults = [];

    /**
     * Creates a new cookie instance.
     *
     * @param array  $config
     * @param string $key
     *
     * @return void
     */
    public function __construct(array $config = [], $key = null)
    {
        // Defining default settings
        $sentryDefaults = [
            'domain'    => '',
            'path'      => '/',
            'secure'    => false,
            'http_only' => false,
        ];

        // Merge settings
        $this->defaults = array_merge($sentryDefaults, $config);

        if (isset($key)) {
            $this->key = $key;
        }
    }

    /**
     * Returns the cookie key.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Put a value in the Sentry cookie.
     *
     * @param mixed $value
     * @param int   $minutes
     *
     * @return void
     */
    public function put($value, $minutes)
    {
        $this->setCookie($value, $this->minutesToLifetime($minutes));
    }

    /**
     * Put a value in the Sentry cookie forever.
     *
     * @param mixed $value
     *
     * @return void
     */
    public function forever($value)
    {
        // Forever can set a cookie for 5 years.
        // This should suffice "forever".
        $this->put($value, 2628000);
    }

    /**
     * Get the Sentry cookie value.
     *
     * @return mixed
     */
    public function get()
    {
        return $this->getCookie();
    }

    /**
     * Remove the Sentry cookie.
     *
     * @return void
     */
    public function forget()
    {
        $this->put(null, -2628000);
    }

    /**
     * Takes a minutes parameter (relative to now)
     * and converts it to a lifetime (unix timestamp).
     *
     * @param int $minutes
     *
     * @return int
     */
    public function minutesToLifetime($minutes)
    {
        return time() + $minutes;
    }

    /**
     * Actually sets the cookie.
     *
     * @param mixed  $value
     * @param int    $lifetime
     * @param string $path
     * @param string $domain
     * @param bool   $secure
     * @param bool   $httpOnly
     *
     * @return void
     */
    public function setCookie($value, $lifetime, $path = null, $domain = null, $secure = null, $httpOnly = null)
    {
        // Default parameters
        if (!isset($path)) {
            $path = $this->defaults['path'];
        }
        if (!isset($domain)) {
            $domain = $this->defaults['domain'];
        }
        if (!isset($secure)) {
            $secure = $this->defaults['secure'];
        }
        if (!isset($httpOnly)) {
            $httpOnly = $this->defaults['http_only'];
        }

        setcookie($this->getKey(), serialize($value), $lifetime, $path, $domain, $secure, $httpOnly);
    }

    /**
     * Returns the cookie from the $_COOKIE array.
     *
     * @return mixed
     */
    public function getCookie()
    {
        if (isset($_COOKIE[$this->getKey()])) {
            return unserialize($_COOKIE[$this->getKey()]);
        }
    }
}
