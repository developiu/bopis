<?php

namespace XPort\Mvc\Controller;

use XPort\Mvc\ControllerInterface;
use League\Plates\Engine as RendererEngine;

class Dashboard implements ControllerInterface
{
    /** @var RendererEngine */
    private $renderer;

    public function __construct(RendererEngine $renderer)
    {
        $this->renderer = $renderer;
    }

    public function getRenderer()
    {
        return $this->renderer;
    }

    public function index()
    {
        echo $this->getRenderer()->render('dashboard/index');
    }
    
}