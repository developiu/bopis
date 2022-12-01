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
        $statement = $this->pdo->prepare(
            'UPDATE stores set alias=:alias, addressline1=:addressline1, 
                    addressline2=:addressline2, addressline3=:addressline3, city=:city,
                    county=:county, district=:district, state_or_region=:state_or_region, 
                    postal_code=:postal_code, country_code=:country_code, supply_source_code=:supply_source_code WHERE id=:id');
        if($statement === false) {
            throw new RuntimeException("errore di database");
        }

        $statement->execute([
            ':alias' => $store['alias'] ?? '', ':addressline1' => $store['addressline1'] ?? '',
            ':addressline2' => $store['addressline2'] ?? '',':addressline3' => $store['addressline3'] ?? '',
            ':city' => $store['city'] ?? '', ':county' => $store['county'] ?? '', ':district' => $store['district'] ?? '',
            ':state_or_region' => $store['state_or_region'] ?? '', ':postal_code' => $store['postal_code'] ?? '',
            ':country_code' => $store['country_code'] ?? '', ':supply_source_code' => $store['supply_source_code'] ?? '',
            ':id' => STORE_ID]);

        return $statement->rowCount() > 0;
    }

    public function getAdapter(): PDO
    {
        return $this->pdo;
    }
}