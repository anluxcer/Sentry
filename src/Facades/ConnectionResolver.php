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

use Illuminate\Database\Connection;
use Illuminate\Database\ConnectionResolverInterface;
use PDO;

class ConnectionResolver implements ConnectionResolverInterface
{
    /**
     * The PDO instance.
     *
     * @var PDO
     */
    protected $pdo;

    /**
     * The PDO driver name.
     *
     * @var string
     */
    protected $driver;

    /**
     * The table prefix.
     *
     * @var string
     */
    protected $tablePrefix = '';

    /**
     * The default connection name.
     *
     * @var string
     */
    protected $defaultConnection;

    /**
     * The database connection.
     *
     * @var \Illuminate\Database\Connection
     */
    protected $connection;

    /**
     * Create a new connection resolver.
     *
     * @param \PDO   $pdo
     * @param string $driverName
     * @param string $tablePrefix
     *
     * @return void
     */
    public function __construct(PDO $pdo, $driverName, $tablePrefix = '')
    {
        $this->pdo = $pdo;
        $this->driverName = $driverName;
        $this->tablePrefix = $tablePrefix;
    }

    /**
     * Get a database connection instance.
     *
     * @param string $name
     *
     * @return \Illuminate\Database\Connection
     */
    public function connection($name = null)
    {
        return $this->getConnection();
    }

    /**
     * Get the default connection name.
     *
     * @return string
     */
    public function getDefaultConnection()
    {
        return $this->getConnection();
    }

    /**
     * Set the default connection name.
     *
     * @param string $name
     *
     * @return void
     */
    public function setDefaultConnection($name)
    {
        $this->defaultConnection = $name;
    }

    /**
     * Returns the database connection.
     *
     * @throws \InvalidArgumentException
     *
     * @return \Illuminate\Database\Connection
     */
    public function getConnection()
    {
        if ($this->connection === null) {
            $connection = null;

            // We will now provide the query grammar to the connection.
            switch ($this->driverName) {
                case 'mysql':
                    $connection = '\Illuminate\Database\MySqlConnection';
                    break;

                case 'pgsql':
                    $connection = '\Illuminate\Database\PostgresConnection';
                    break;

                case 'sqlsrv':
                    $connection = '\Illuminate\Database\SqlServerConnection';
                    break;

                case 'sqlite':
                    $connection = '\Illuminate\Database\SQLiteConnection';
                    break;

                default:
                    throw new \InvalidArgumentException("Cannot determine grammar to use based on {$this->driverName}.");
                    break;
            }

            $this->connection = new $connection($this->pdo, '', $this->tablePrefix);
        }

        return $this->connection;
    }
}
