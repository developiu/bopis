<?php

namespace XPort;

use DomainException;

class StringUtils
{
    /**
     * Ritorna la stringa $str troncata al dato numero di caratteri, eventualmente seguita da un '...' se è
     * avvenuta una troncatura e se $ellipsis vale true
     *
     * @param string $str
     * @param int $limit
     * @param bool $ellipsis
     * @return string
     */
    public static function truncate($str, $limit, $ellipsis=true)
    {
        if (strlen($str) > $limit) {
            $str = wordwrap($str, $limit);
            $str = substr($str, 0, strpos($str, "\n"));
            $str .= $ellipsis ? "..." : "";
        }

        return $str;
    }

    /**
     * Prende in input una stringa contenente un csv multi linea, dove ogni riga
     * è separata da una sequenza di caratteri in $delimiter e la converte in un array
     * di array.
     *
     * @param string $csv
     * @param string $delimiter
     * @return array
     */
    public static function convertCsvToArray(string $csv, string $delimiter="\r\n"):array
    {
        $csv = str_replace(str_split($delimiter,1), "\n", $csv);
        $csv = array_filter(explode("\n",$csv));

        $data = [];
        foreach($csv as $line) {
            $data[] = str_getcsv($line);
        }

        return $data;
    }

    /**
     * Ritorna la traduzione italiana dello stato di un ordine.
     *
     * @param string $status
     * @return string|null
     */
    public static function italianStatus(string $status) :?string
    {
        switch ($status) {
            case 'NEW':
                return 'Nuovo';
            case 'READY_FOR_PICKUP':
                return 'Pronto per il ritiro';
            case 'PICKED_UP':
                return 'Ritirato';
            case 'REFUSED':
                return 'Rifiuto di ritiro';
            default:
                return null;
        }
    }

}