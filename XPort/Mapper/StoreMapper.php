<?php

namespace XPort\Mapper;

use DomainException;
use RuntimeException;
use PDO;

class StoreMapper
{
    /** @var PDO */
    private $pdo;

    public function __construct()
    {
         $dsn = 'mysql:host=' . DBHOST . ';dbname=' . DBNAME;
         $this->pdo = new PDO( $dsn,DBUSER, DBPASS);
    }

    /**
     * Ritorna lo store corrente
     *
     * @return array
     * @throws RuntimeException in caso di errore di sintassi SQL o di connessione al db
     */
    public function load()
    {
        $statement = $this->pdo->prepare('SELECT * FROM stores WHERE id=:store');
        if($statement === false) {
            throw new RuntimeException("errore di database");
        }

        $statement->execute([':store' => STORE_ID]);

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Salva i dati contenuti nell'array $store nello store corrente. Ritorna true se lo store è stato modificato,
     * false altrimenti.
     *
     * @param array $store
     * @return bool
     * @throws RuntimeException In caso di errore di sintassi SQL o di connessione al db
     * @throws DomainException Se $store non contiene tutti i dati richiesti
     */
    public function save($store)
    {
        $statement = $this->pdo->prepare('UPDATE stores set name=:name, address=:address WHERE id=:id');
        if($statement === false) {
            throw new RuntimeException("errore di database");
        }

        $statement->execute([':name' => $store['name'], ':address' => $store['address'], ':id' => STORE_ID]);

        return $statement->rowCount() > 0;
    }

    public function getAdapter(): PDO
    {
        return $this->pdo;
    }
}