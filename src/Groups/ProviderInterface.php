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

interface ProviderInterface
{
    /**
     * Find the group by ID.
     *
     * @param int $id
     *
     * @throws \Cartalyst\Sentry\Groups\GroupNotFoundException
     *
     * @return \Cartalyst\Sentry\Groups\GroupInterface $group
     */
    public function findById($id);

    /**
     * Find the group by name.
     *
     * @param string $name
     *
     * @throws \Cartalyst\Sentry\Groups\GroupNotFoundException
     *
     * @return \Cartalyst\Sentry\Groups\GroupInterface $group
     */
    public function findByName($name);

    /**
     * Returns all groups.
     *
     * @return array $groups
     */
    public function findAll();

    /**
     * Creates a group.
     *
     * @param array $attributes
     *
     * @return \Cartalyst\Sentry\Groups\GroupInterface
     */
    public function create(array $attributes);
}
