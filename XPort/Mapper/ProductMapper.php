<?php

namespace XPort\Mapper;

class ProductMapper
{
    /** @var \PDO */
    private $pdo;

    public function __construct()
    {
         $dsn = 'mysql:host=' . DBHOST . ';dbname=' . DBNAME;
         $this->pdo = new \PDO( $dsn,DBUSER, DBPASS);
    }

    /**
     * @return array|false
     */
    public function fetchAll()
    {
        $statement = $this->pdo->query('SELECT * FROM products ORDER BY id desc');
        if($statement === false) {
            return false;
        }

        return $statement->fetchAll();
    }
}