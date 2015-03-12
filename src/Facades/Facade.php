<?php

/*
 * This file is part of Sentry.
 *
 * (c) Cartalyst LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cartalyst\Sentry\Facades;

abstract class Facade
{
    /**
     * Sentry instance.
     *
     * @var \Cartalyst\Sentry\Sentry
     */
    protected static $instance;

    /**
     * Returns the Sentry instance registered with the Facade.
     *
     * @return \Cartalyst\Sentry\Sentry
     */
    public static function instance()
    {
        if (static::$instance === null) {
            static::$instance = forward_static_call_array(
                [get_called_class(), 'createSentry'],
                func_get_args()
            );
        }

        return static::$instance;
    }

    /**
     * Handle dynamic, static calls to the object.
     *
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        $instance = static::instance();

        switch (count($args)) {
            case 0:
                return $instance->$method();

            case 1:
                return $instance->$method($args[0]);

            case 2:
                return $instance->$method($args[0], $args[1]);

            case 3:
                return $instance->$method($args[0], $args[1], $args[2]);

            case 4:
                return $instance->$method($args[0], $args[1], $args[2], $args[3]);

            default:
                return call_user_func_array([$instance, $method], $args);
        }
    }
}
