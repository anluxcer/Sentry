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

use Illuminate\Session\Store as SessionStore;

class IlluminateSession implements SessionInterface
{
    /**
     * The key used in the Session.
     *
     * @var string
     */
    protected $key = 'cartalyst_sentry';

    /**
     * Session store object.
     *
     * @var \Illuminate\Session\Store
     */
    protected $session;

    /**
     * Creates a new Illuminate based Session driver for Sentry.
     *
     * @param \Illuminate\Session\Store $session
     * @param string                    $key
     *
     * @return void
     */
    public function __construct(SessionStore $session, $key = null)
    {
        $this->session = $session;

        if (isset($key)) {
            $this->key = $key;
        }
    }

    /**
     * Returns the session key.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Put a value in the Sentry session.
     *
     * @param mixed $value
     *
     * @return void
     */
    public function put($value)
    {
        $this->session->put($this->getKey(), $value);
    }

    /**
     * Get the Sentry session value.
     *
     * @return mixed
     */
    public function get()
    {
        return $this->session->get($this->getKey());
    }

    /**
     * Remove the Sentry session.
     *
     * @return void
     */
    public function forget()
    {
        $this->session->forget($this->getKey());
    }
}
