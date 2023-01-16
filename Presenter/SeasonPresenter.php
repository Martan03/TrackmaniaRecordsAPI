<?php

class SeasonPresenter extends Presenter
{
    public function process(array $params): void
    {
        switch (strtoupper($_SERVER['REQUEST_METHOD']))
        {
            case 'GET':
                $this->processGet($params);
                exit;
            default:
                $this->sendOutput('', array('HTTP/1.1 404 Not Found'));
                exit;
        }
    }

    /**
     * Processes GET request method
     */
    public function processGet(array $params)
    {
        $seasonManager = new SeasonManager();

        // if no parameters given, return all season
        if (empty($params))
        {
            $this->sendOutput(
                json_encode($seasonManager->getSeasons()),
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
            exit;
        }

        switch ($params[0])
        {
            case 'remove':
                array_shift($params);
                $this->sendOutput(
                    json_encode(array(
                        'remove' => $seasonManager->removeSeason($_GET))
                    ),
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );
                exit;
            case 'manage':
                $this->sendOutput(
                    json_encode($seasonManager->submitDialog($_GET)),
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );
                exit;
            case 'get':
                $this->sendOutput(
                    json_encode($seasonManager->getSeason($_GET)),
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );
                exit;
            default:
                $this->sendOutput('', array('HTTP/1.1 404 Not Found'));
                exit;
        }
    }
}