<?php
declare(strict_types=1);

namespace Smile\Anonymizer\Tests;

use Doctrine\DBAL\Connection;
use Smile\Anonymizer\Dumper\Sql\Config\DatabaseConfig;
use Smile\Anonymizer\Dumper\Sql\Doctrine\ConnectionFactory;

abstract class DatabaseTestCase extends TestCase
{
    /**
     * @var Connection
     */
    protected static $connection;

    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass()
    {
        if (!static::canRunDatabaseTests()) {
            static::markTestSkipped('Skip database tests.');
        }

        // We can use a shared connection to speed up the tests, there are only SELECT queries
        if (static::$connection !== null) {
            return;
        }

        $config = new DatabaseConfig(static::getConnectionParams());

        static::$connection = ConnectionFactory::create($config);

        // Create the tables
        $queries = file_get_contents(APP_ROOT . '/tests/Resources/db/test_db.sql');
        $statement = static::$connection->prepare($queries);
        $statement->execute();
    }

    /**
     * Get the database configuration.
     *
     * @return array
     */
    public static function getConnectionParams(): array
    {
        return [
            'driver' => $GLOBALS['db_driver'],
            'host' => $GLOBALS['db_host'],
            'port' => $GLOBALS['db_port'],
            'user' => $GLOBALS['db_user'],
            'password' => $GLOBALS['db_password'],
            'name' => $GLOBALS['db_name'],
        ];
    }

    /**
     * Check if the database tests should be performed.
     *
     * @return bool
     */
    public static function canRunDatabaseTests(): bool
    {
        return (bool) $GLOBALS['run_database_tests'];
    }

    /**
     * Get the connection object.
     *
     * @return Connection
     */
    protected function getConnection(): Connection
    {
        return static::$connection;
    }
}