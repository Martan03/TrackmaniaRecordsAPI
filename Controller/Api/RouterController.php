<?php

class RouterController extends BaseController
{
    protected BaseController $controller;

    public function process(array $params): void
    {
        $parsedURL = $this->parseURL($params[0]);
    }

    private function parseURL(string $url) : array
    {
        $parsedURL = parse_url($url);
        return explode("/", trim(ltrim($parsedURL["path"], "/")));
    }

    private function dashesIntoCamelNotation(string $txt) : string
    {
        return str_replace(' ', '', ucwords(str_replace('-', '', $txt)));
    }
}