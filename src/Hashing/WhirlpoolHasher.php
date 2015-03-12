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

class WhirlpoolHasher extends BaseHasher implements HasherInterface
{
    /**
     * Salt Length.
     *
     * @var int
     */
    public $saltLength = 16;

    /**
     * Hash string.
     *
     * @param string $string
     *
     * @return string
     */
    public function hash($string)
    {
        // Create salt
        $salt = $this->createSalt();

        return $salt.hash('whirlpool', $salt.$string);
    }

    /**
     * Check string against hashed string.
     *
     * @param string $string
     * @param string $hashedString
     *
     * @return bool
     */
    public function checkhash($string, $hashedString)
    {
        $salt = substr($hashedString, 0, $this->saltLength);

        return $this->slowEquals($salt.hash('whirlpool', $salt.$string), $hashedString);
    }
}
