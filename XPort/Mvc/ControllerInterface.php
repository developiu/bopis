<?php

namespace XPort\Mvc;

use League\Plates\Engine as RendererEngine;

interface ControllerInterface
{
    public function __construct(RendererEngine $renderer);

    /**
     * @return RendererEngine
     */
    public function getRenderer();
}