<?php

namespace Frankie\Factory;

use Frankie\Response\ResponseInterface;

abstract class ResponseFactory
{
    protected $body;

    abstract public function setBody($body): self;

    abstract public function get(): ResponseInterface;
}
