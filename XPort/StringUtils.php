<?php

namespace XPort;

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

}