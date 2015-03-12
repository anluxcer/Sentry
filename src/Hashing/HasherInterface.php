<?php

/*
 * This file is part of Sentry.
 *
 * (c) Cartalyst LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cartalyst\Sentry\Hashing;

interface HasherInterface
{
    /**
     * Hash string.
     *
     * @param string $string
     *
     * @return string
     */
    public function hash($string);

    /**
     * Check string against hashed string.
     *
     * @param string $string
     * @param string $hashedString
     *
     * @return bool
     */
    public function checkhash($string, $hashedString);
}
