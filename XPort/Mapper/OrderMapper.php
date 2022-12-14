<?php

namespace XPort\Mapper;

use DomainException;
use LogicException;
use PDO;
use RuntimeException;

class OrderMapper
{
    /** @var PDO */
    private $pdo;

    /** @var array */
    private static $allowedStatusTransitions = [
        'NEW' => [ 'NEW', 'CANCELLED', 'READY_FOR_PICKUP'],
        'CANCELLED' => ['CANCELLED'],
        'READY_FOR_PICKUP' => ['READY_FOR_PICKUP', 'PICKED_UP','REFOUNDED'],
        'PICKED_UP' => ['PICKED_UP', 'REFOUNDED'],
        'REFOUNDED' => ['REFOUNDED']
    ];

    /** @var string[] */
    private array $statuses = ['NEW', 'CANCELLED', 'READY_FOR_PICKUP', 'PICKED_UP', 'REFOUNDED'];

    public function __construct()
    {
         $dsn = 'mysql:host=' . DBHOST . ';dbname=' . DBNAME;
         $this->pdo = new PDO( $dsn,DBUSER, DBPASS);
    }

    /**
     * @return array|false
     */
    public function fetchAll()
    {
        $statement = $this->pdo->query('SELECT * FROM orders ORDER BY id desc');
        if($statement === false) {
            return false;
        }

        return $statement->fetchAll();
    }

    /**
     *  Annulla l'ordine di dato id: se l'ordine è compatibile con lo stato CANCELLED, mettilo CANCELLED, ALTRIMENTI
     * mettilo REFOUNDED.
     *
     * @param string $id
     * @throws RuntimeException In caso di errore nell'esecuzione della query
     */
    public function cancelOrder($id)
    {
        $updated = $this->updateOrderStatus([ $id ], 'CANCELLED');
        if(!$updated) {
            $this->updateOrderStatus([ $id ], 'REFOUNDED');
        }
    }

    /**
     * Aggiorna lo status dell'ordine di dato id.
     *
     * @param string|array $id L'id dell'ordine da aggiornare; se è un array aggiorna l'ordine di tutti gli ordini corrispondenti
     *                         Se qualcuno di questi ordini ha uno stato corrente che non è compatibile con il nuovo stato richiesto, quest'ordine
     *                         viene silenziosamente ignorato
     * @param string $newStatus il nuovo stato dell'ordine (deve essere uno dei valori in $statuses)
     * @return bool True se l'ordine è stato modificato, False altrimenti
     * @throws RuntimeException In caso di errore nell'esecuzione della query
     * @throws DomainException Se $newStatus non è uno degli stati ammessi
     *
     */
    public function updateOrderStatus($id, $newStatus)
    {
        if(!in_array($newStatus, $this->statuses)) {
            throw new DomainException("'$newStatus' non è uno stato ammesso per un ordine");
        }
        if(is_string($id)) {
            $id = [ $id ];
        }

        $statusesFrom = self::OrderStatusFrom($newStatus);
        $frm = implode(", ", array_fill(0,count($statusesFrom), "?"));
        $in = implode(", ", array_fill(0, count($id), "?"));
        $statement = $this->pdo->prepare("UPDATE orders set status=? WHERE id IN ($in) AND status IN ($frm)" );
        if($statement === false) {
            throw new RuntimeException("Errore di database");
        }
        $statement->execute(array_merge([$newStatus],$id,$statusesFrom));

        return $statement->rowCount() > 0;
    }

    /**
     * Ritorna gli ordini, tra quelli di id in $ids, il cui stato corrente rende possibile di passare allo stato
     * $newStatus.
     *
     * @param string $newStatus
     * @param array $ids
     * @return array
     * @throws DomainException Se $newStatus non è uno degli stati ammessi
     * @throws RuntimeException In caso di errore nell'esecuzione della query
     */
    public function ordersCompatibleWithNewStatus($newStatus, $ids)
    {
        if(!in_array($newStatus, $this->statuses)) {
            throw new DomainException("'$newStatus' non è uno stato ammesso per un ordine");
        }

        if(empty($ids)) {
            return $ids;
        }

        $statusesFrom = self::OrderStatusFrom($newStatus);
        $frm = implode(", ", array_fill(0,count($statusesFrom), "?"));
        $in = implode(", ", array_fill(0, count($ids), "?"));

        $statement = $this->pdo->prepare("SELECT * FROM orders WHERE id in ($in) AND status IN ($frm)");
        if($statement === false) {
            throw new RuntimeException("Errore di database");
        }

        $statement->execute(array_merge($ids,$statusesFrom));
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllowedStatuses()
    {
        return $this->statuses;
    }

    /**
     * Ritorna un array con tutti gli stati cui può andare un ordine che ha stato corrente $newStatus
     *
     * @param string $newStatus
     * @return string
     * @throws DomainException Se $newStatus non è uno degli stati ammessi
     */
    public static function OrderStatusTo($newStatus)
    {
        if(!isset(self::$allowedStatusTransitions[$newStatus])) {
            throw new DomainException("'$newStatus' non è uno stato ammesso per un ordine");
        }

        return self::$allowedStatusTransitions[$newStatus];
    }

    /**
     * Ritorna un array con tutti gli stati da cui un ordine può andare nello stato $newStatus
     *
     * @param string $newStatus
     * @return string
     * @throws DomainException Se $newStatus non è uno degli stati ammessi
     */
    public static function OrderStatusFrom($newStatus)
    {
        if(!isset(self::$allowedStatusTransitions[$newStatus])) {
            throw new DomainException("'$newStatus' non è uno stato ammesso per un ordine");
        }

        $statusesFrom = [];
        foreach (self::$allowedStatusTransitions as $statusFrom=>$statusesTo) {
            if(in_array($newStatus, $statusesTo)) {
                $statusesFrom[]=$statusFrom;
            }
        }

        return array_unique($statusesFrom);
    }

    public function getAdapter(): PDO
    {
        return $this->pdo;
    }
}