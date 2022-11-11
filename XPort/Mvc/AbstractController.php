<?php

namespace XPort\Mvc;

use League\Plates\Engine as RendererEngine;

class AbstractController implements ControllerInterface
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
    }}