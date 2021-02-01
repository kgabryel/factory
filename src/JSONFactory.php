<?php

namespace Frankie\Factory;

use Frankie\Response\Response;
use Frankie\Response\ResponseInterface;

class JSONFactory extends ResponseFactory
{
    public function setBody($body): self
    {
        $this->body = json_encode($body, JSON_THROW_ON_ERROR | true);
        return $this;
    }

    public function get(): ResponseInterface
    {
        return (new Response())->withBody($this->body)
            ->withHeader('Content-Type', 'application/json; charset=utf-8');
    }
}
