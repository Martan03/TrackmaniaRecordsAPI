<?php

class BaseController
{
    public function __call($name, $arguments)
    {
        $this->sendOutput('', array('HTTP/1.1 404 Not Found'));
    }

    protected function getUriSegments()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return explode("/", $uri);
    }

    protected function getQueryStringParams()
    {
        return parse_str($_SERVER['QUERY_STRING'], $query);
    }

    protected function sendOutput($data, $httpHeaders = array())
    {
        header_remove("Set-Cookie");

        if (is_array($httpHeaders))
            foreach ($httpHeaders as $header)
                header($header);
        
        echo $data;
        exit();
    }
}