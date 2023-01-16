<?php

class RecordPresenter extends Presenter
{
    public function process(array $params) : void
    {
        switch (strtoupper($_SERVER['REQUEST_METHOD']))
        {
            case 'GET':
                $this->processGet($params);
                break;
            default:
                $this->sendOutput('', array('HTTP/1.1 404 Not Found'));
                break;
        }
        exit;
    }

    public function processGet(array $params) : void
    {
        $recordManager = new RecordManager();

        // if no parameters given, return all records
        if (empty($params))
        {
            $this->sendOutput(
                json_encode($recordManager->getRecords()),
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
            exit;
        }

        switch ($params[0])
        {
            case 'remove':
                $this->sendOutput(
                    json_encode(array(
                        'remove' => $recordManager->removeRecord($_GET))
                    ),
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );
                exit;
            case 'manage':
                $this->sendOutput(
                    json_encode($recordManager->submitDialog($_GET)),
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );
                exit;
            case 'get':
                $this->sendOutput(
                    json_encode($recordManager->getRecord($_GET)),
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );
                exit;
            default:
                $this->sendOutput('', array('HTTP/1.1 404 Not Found'));
                exit;
        }
    }
}