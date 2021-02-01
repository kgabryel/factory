<?php

namespace Frankie\Factory;

use Frankie\Response\Response;
use Frankie\Response\ResponseInterface;
use SimpleXMLElement;

class XMLFactory extends ResponseFactory
{
    private const PREFIX_KEY = 'XML_PREFIX';
    private const ROOT_KEY = 'XML_ROOT';

    public function setBody($body): ResponseFactory
    {
        if (getenv(self::ROOT_KEY) && getenv(self::ROOT_KEY) !== '') {
            $root = getenv(self::ROOT_KEY);
        } else {
            $root = 'root';
        }
        $this->body = json_decode(
            json_encode($body, JSON_THROW_ON_ERROR | true),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
        if ($this->body === null) {
            $this->body = [];
        }
        if (!\is_array($this->body)) {
            $this->body = [$this->body];
        }
        $xml = new SimpleXMLElement("<?xml version=\"1.0\"?><$root></$root>");
        $this->arrayToXML($this->body, $xml);
        $this->body = $xml->asXML();
        return $this;
    }

    public function get(): ResponseInterface
    {
        return (new Response())->withBody($this->body)
            ->withHeader('Content-Type', 'application/xml; charset=utf-8');
    }

    private function arrayToXML(array $data, SimpleXMLElement $xml): void
    {
        if (getenv(self::PREFIX_KEY) && getenv(self::PREFIX_KEY) !== '') {
            $prefix = getenv(self::PREFIX_KEY);
        } else {
            $prefix = 'item';
        }
        foreach ($data as $key => $value) {
            if (is_numeric($key)) {
                $key = $prefix . ++$key;
            }
            if (\is_array($value)) {
                $this->arrayToXML($value, $xml->addChild($key));
            } else {
                $xml->addChild($key, htmlspecialchars($value));
            }
        }
    }
}
