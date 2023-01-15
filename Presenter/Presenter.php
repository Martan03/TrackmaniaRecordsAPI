<?php

abstract class Presenter
{
    /**
     * Magic method - called when call method that doesn't exist
     */
    public function _call($name, $args)
    {
        $this->sendOutput('', array('HTTP/1.1 404 Not Found'));
    }

    abstract function process(array $params) : void;

    /**
     * Sends API response
     * @param $data data to be shown/send
     * @param $httpHeaders HTTP headers
     */
    protected function sendOutput($data, $httpHeaders = array()) : void
    {
        header_remove('Set-Cookie');

        if (is_array($httpHeaders) && !empty($httpHeaders))
        {
            foreach ($httpHeaders as $httpHeader)
                header($httpHeader);
        }

        echo $data;
        exit;
    }
}