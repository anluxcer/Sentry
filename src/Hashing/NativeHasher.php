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

class NativeHasher implements HasherInterface
{
    /**
     * Array of alternative hashing functions.
     */
    private static $fallbacks = [];

    /**
     * Add Fallback.
     *
     * Add an alternative hashing function for verifying passwords.
     *
     * @param string   $algo
     * @param function $function
     * @param array    $options
     */
    public static function addFallback($algo, $function, $options = [])
    {
        static::$fallbacks[$algo] = [
            'function' => $function,
            'options'  => $options,
        ];
    }

    /**
     * Hash string.
     *
     * @param string $string
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    public function hash($string)
    {
        // Usually caused by an old PHP environment, see
        // https://github.com/cartalyst/sentry/issues/98#issuecomment-12974603
        // and https://github.com/ircmaxell/password_compat/issues/10
        if (!function_exists('password_hash')) {
            throw new \RuntimeException('The function password_hash() does not exist, your PHP environment is probably incompatible. Try running [vendor/ircmaxell/password-compat/version-test.php] to check compatibility or use an alternative hashing strategy.');
        }

        if (($hash = password_hash($string, PASSWORD_DEFAULT)) === false) {
            throw new \RuntimeException('Error generating hash from string, your PHP environment is probably incompatible. Try running [vendor/ircmaxell/password-compat/version-test.php] to check compatibility or use an alternative hashing strategy.');
        }

        return $hash;
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
        $verified = password_verify($string, $hashedString);
        if (!$verified) {
            // Try the fallbacks
            foreach (static::$fallbacks as $algo => $fallback) {
                $verified = $fallback['function']($string, $hashedString, $fallback['options']);
                if ($verified) {
                    break;
                }
            }
        }

        return $verified;
    }

    /**
     * Check if the algorithm of the input should be upgraded.
     *
     * @param string $hashedString
     *
     * @return bool
     */
    public function needsRehashed($hashedString)
    {
        return password_needs_rehash($hashedString, PASSWORD_DEFAULT);
    }
}
