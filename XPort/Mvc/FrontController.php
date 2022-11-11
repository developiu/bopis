<?php

namespace XPort\Mvc;

use League\Plates\Engine as RendererEngine;

class FrontController
{
    /** @var RendererEngine */
    private $renderer;

    const CONTROLLER_BASE_NS = '\XPort\Mvc\Controller';

    public function __construct(RendererEngine $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * Inizializza il controller identificato dalla string $controller e ne lancia il metodo identificato da $action.
     * $controller viene convertito in CamelCase e la classe cercata in Xport\Mvc\Controller; $action viene convertito
     * in camelCase. In caso di errore viene lanciata una Exception
     *
     * @param string $controller
     * @param string $action
     * @return void
     * @throws MvcException
     */
    public function process($controller, $action)
    {
        $controllerClassName = self::CONTROLLER_BASE_NS . '\\' . ucfirst($this->convertUnderscoresTocamelCase($controller));
        if(!class_exists($controllerClassName)) {
            throw new MvcException("Controller inesistente: '$controllerClassName'");
        }
        $controllerObject = new $controllerClassName($this->renderer);
        if(! ($controllerObject instanceof ControllerInterface) ) {
            throw new MvcException("'$controllerClassName' non implementa 'ControllerInterface'");
        }
        $methodName = $this->convertUnderscoresTocamelCase($action);
        if(!method_exists($controllerObject, $methodName)) {
            throw new MvcException("Azione non implementata: '$methodName'");
        }

        $controllerObject->$methodName();
    }

    /**
     * Converte undersconre a camelCase: per esempio 'una_stringa'
     * diventa 'unaStringa'
     *
     * @param string $str
     * @return string
     */
    private function convertUnderscoresTocamelCase($str)
    {
        return lcfirst(str_replace(' ','',ucwords(str_replace('_',' ',$str))));
    }
}