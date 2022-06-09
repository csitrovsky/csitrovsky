<?php

namespace app\src;

use PDO;
use PDOStatement;
use function count;
use function implode;
use function strtoupper;

/**
 * @property string $_query
 */
class Database
{

    /**
     * @var string
     */
    private string $hostname = 'localhost';

    /**
     * @var string
     */
    private string $database = 'root';

    /**
     * @var string
     */
    private string $username = 'root';

    /**
     * @var string
     */
    private string $password = '';

    /**
     * @param       $sql
     * @param array $params
     *
     * @return bool|PDOStatement
     */
    public function query($sql, array $params = []): bool|PDOStatement
    {
        $statement = $this->connect()->prepare($sql);
        $statement->execute($params);
        return $statement;
    }

    /**
     * @return PDO
     */
    private function connect(): PDO
    {
        $pdo = new PDO(
            "mysql:host=$this->hostname;dbname=$this->database;charset=utf8;",
            $this->username, $this->password
        );
        $pdo->setAttribute(PDO::MYSQL_ATTR_FOUND_ROWS, true);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    }

    /**
     * @param string $from
     * @param array  $params
     *
     * @return void
     */
    public function select(string $from = '', array $params = []): void
    {
        $this->_query = 'SELECT ' . ((count($params) > 0) ? implode(', ', $params) : '*') . " FROM `$from`";
    }

    /**
     * @param array $params
     *
     * @return void
     */
    public function where(array $params = []): void
    {
        if (count($params)) {
            $this->_query .= ' WHERE ' . implode(' AND ', $params);
        }
    }

    /**
     * @param array $params
     *
     * @return void
     */
    public function order_by(array $params = []): void
    {
        if (count($params)) {
            $this->_query .= ' ORDER BY ' . implode(', ', $params);
        }
    }

    /**
     * @param array $params
     *
     * @return void
     */
    public function group_by(array $params = []): void
    {
        if (count($params)) {
            $this->_query .= ' GROUP BY ' . implode(', ', $params);
        }
    }

    /**
     * @param string $join
     * @param string $table
     * @param array  $params
     *
     * @return void
     */
    public function join(string $join, string $table, array $params = []): void
    {
        $this->_query .= ' ' . strtoupper($join) . " JOIN `$table` ON (" . implode(' AND ', $params) . ')';
    }
}