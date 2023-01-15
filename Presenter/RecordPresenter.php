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
        $seasonManager = new SeasonManager();

        // loads all records when no parameters or get arguments are given
        if (empty($params) && empty($_GET))
        {
            $this->sendOutput(
                json_encode($recordManager->getRecords()),
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
            exit;
        }

        // manages record
        if ($params[0] == 'manage' &&
            isset($_GET['record_holder']) && !empty($_GET['record_holder']) &&
            isset($_GET['record_time']) && !empty($_GET['record_time']) &&
            isset($_GET['record_season']) && !empty($_GET['record_season']) &&
            isset($_GET['record_level']) && !empty($_GET['record_level'])
        )
        {
            $data = array(
                'record_id' => '',
                'record_holder' => $_GET['record_holder'],
                'record_time' => $_GET['record_time'],
                'record_season' => $_GET['record_season'],
                'record_level' => $_GET['record_level']
            );

            if (isset($_GET['record_id']) && !empty($_GET['record_id']))
                $data['record_id'] = $_GET['record_id'];

            $response = $recordManager->submitDialog($data);
            if (empty($response))
                $httpHeader = array('Content-Type: application/json', 'HTTP/1.1 200 OK');
            else
                $httpHeader = array('HTTP/1.1 404 Not Found');

            $this->sendOutput(
                json_encode($response),
                $httpHeader
            );
            exit;
        }
        else if ($params[0] == 'remove' &&
                 isset($_GET['record_id']) && !empty($_GET['record_id']))
        {
            $recordManager->removeRecord($_GET['record_id']);
            $this->sendOutput(
                '[]',
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
            exit;
        }
        else if (isset($_GET) && !empty($_GET) && empty($params))
        {
            if (isset($_GET['year']) && !empty($_GET['year']) &&
                isset($_GET['name']) && !empty($_GET['name']))
            {
                $season = $seasonManager->getSeason($_GET['year'], $_GET['name']);
                if (!$season)
                    $this->sendOutput('', array('HTTP/1.1 404 Not Found'));

                if (isset($_GET['level']) && !empty($_GET['level']))
                {
                    $this->sendOutput(
                        json_encode($recordManager->getRecordsBySeasonLevel(
                            $season['season_id'],
                            $_GET['level']
                        )),
                        array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                    );
                    exit;
                }

                $this->sendOutput(
                    json_encode($recordManager->getSeasonLevelsRecords(
                        $season['season_id']
                    )),
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );
                exit;
            }
        }
        $this->sendOutput('', array('HTTP/1.1 404 Not Found'));
        exit;
    }
}