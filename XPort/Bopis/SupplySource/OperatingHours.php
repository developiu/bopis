<?php

namespace XPort\Bopis\SupplySource;

use DomainException;

class OperatingHours
{

    /** @var array un array del tipo: [ monday => ['startTime' => '08:00', 'endTime' => '10:00' ], ... ] se un giorno
        non è previsto la chiave corrispondente manca
     */
    private array $hoursTable;

    public function __construct(array $data)
    {
        $this->hoursTable = [];
        foreach($data as $day=>$start_end) {
            $this->setDay($day, $start_end['startTime'], $start_end['endTime']);
        }
    }
    
    /**
     * Imposta l'orario per il giorno corrispondente.
     *
     * @param string $day
     * @param string $start
     * @param string $end
     * throws DomainException se il formato di $day, $start o $end non è corretto
     */
    public function setDay(string $day, string $start, string $end)
    {
        if(!$this->isValidWeekDay($day)) {
            throw new DomainException("'$day' non è un giorno della settimana valido");
        }

        $timeRegexp = '/^[0-9][0-9]:[0-9][0-9]$/';
        if(preg_match($timeRegexp, $start) === 0) {
            throw new DomainException("'$start' non è un formato orario valido");
        }

        if(preg_match($timeRegexp, $end) === 0) {
            throw new DomainException("'$end' non è un formato orario valido");
        }

        $this->hoursTable[$day][0]=['startTime' => $start, 'endTime' => $end];
    }

    /**
     * Ritorna l'ora d'inizio di $day, o null se non è stata definita
     *
     * @param string $day
     * @return string|null
     * @throws DomainException se il formato di $day non è corretto
     */
    public function getStartTime(string $day): ?string
    {
        if(!$this->isValidWeekDay($day)) {
            throw new DomainException("'$day' non è un giorno della settimana valido");
        }

        return $this->hoursTable[$day]['startTime'] ?? null;
    }


    /**
     * Ritorna l'ora di fine di $day, o null se non è stata definita
     *
     * @param string $day
     * @return string|null
     * @throws DomainException se il formato di $day non è corretto
     */
    public function getEndTime(string $day): ?string
    {
        if(!$this->isValidWeekDay($day)) {
            throw new DomainException("'$day' non è un giorno della settimana valido");
        }

        return $this->hoursTable[$day]['endTime'] ?? null;
    }

    /**
     * @param $day
     * @return bool
     * @throws DomainException se il formato di $day
     */
    public function isDefined($day)
    {
        if(!$this->isValidWeekDay($day)) {
            throw new DomainException("'$day' non è un giorno della settimana valido");
        }

        return isset($this->hoursTable[$day]);
    }

    /**
     * @param string $day
     * @return void
     * @throws DomainException se il formato di $day
     */
    public function removeDay(string $day) {
        if(!$this->isValidWeekDay($day)) {
            throw new DomainException("'$day' non è un giorno della settimana valido");
        }

        unset($this->hoursTable[$day]);
    }


    public function toArray()
    {
        return $this->hoursTable;
    }

    /**
     * Ritorna true o false a seconda che $day sia un giorno della settimana valido oppure no
     *
     * @param $day
     * @return bool
     */
    private function isValidWeekDay($day)
    {
        $allowedDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        if(array_search($day, $allowedDays) === false) {
           return false;
        }

        return true;
    }

}