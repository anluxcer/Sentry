<?php

/*
 * This file is part of Sentry.
 *
 * (c) Cartalyst LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cartalyst\Sentry\Groups;

interface GroupInterface
{
    /**
     * Returns the group's ID.
     *
     * @return mixed
     */
    public function getId();

    /**
     * Returns the group's name.
     *
     * @return string
     */
    public function getName();

    /**
     * Returns permissions for the group.
     *
     * @return array
     */
    public function getPermissions();

    /**
     * Saves the group.
     *
     * @return bool
     */
    public function save();

    /**
     * Delete the group.
     *
     * @return bool
     */
    public function delete();
}
