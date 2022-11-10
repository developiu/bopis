<?php

namespace XPort;

class Auth
{
    private const SESSION_AUTH_KEY = '__AUTH__';

    /**
     * @return bool true se l'utente è loggato, false altrimenti
     */
    public static function isLogged()
    {
        return isset($_SESSION[self::SESSION_AUTH_KEY]) && $_SESSION[self::SESSION_AUTH_KEY];
    }

    /**
     * logga l'utente nel sistema, se la password è quella corretta
     *
     * @param $password
     * @return bool true se l'utente è stato correttamente autenticato, false altrimenti (tipicamente: password errata)
     */
    public static function login($password)
    {
        if($password!=USER_ACCESS_CODE) {
            return false;
        }

        $_SESSION[self::SESSION_AUTH_KEY]=true;
        return true;
    }

    public static function logout()
    {
        unset($_SESSION[self::SESSION_AUTH_KEY]);
    }
}