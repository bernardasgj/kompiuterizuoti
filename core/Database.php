<?php

namespace Core;

use Exception;
use mysqli;

/**
 * Simple MySQL database wrapper using mysqli.
 */
class Database
{
    private string $host = 'db';
    private mysqli $connection;

    /**
     * Establishes a connection to the MySQL database using environment variables.
     * @throws Exception if the connection fails
     */
    public function __construct()
    {
        $user = getenv('KOMPIUTERIZUOTI_USER');
        $password = getenv('KOMPIUTERIZUOTI_PASSWORD');
        $database = getenv('KOMPIUTERIZUOTI_DATABASE');

        $this->connection = new mysqli($this->host, $user, $password, $database);

        if ($this->connection->connect_error) {
            throw new Exception("Connection failed: " . $this->connection->connect_error);
        }

        $this->connection->set_charset('utf8mb4');
    }

    public function getConnection(): mysqli
    {
        return $this->connection;
    }
}
