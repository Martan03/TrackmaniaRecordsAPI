<?php

class RecordManager
{
    /**
     * Loads record from database
     * If record_id, returns record with this id
     * If season_id and level, returns all times of given level
     * If season_id, returns best times from each level of the season
     * @param array $data containing record_id or both season_id and level or season_id
     * @return array loaded record, null if not found
     */
    public function getRecord(array $data) : ?array
    {
        if (isset($data['record_id']) && !empty($data['record_id']))
            return $this->getRecordById($data['record_id']);
        if (isset($data['season_id']) && !empty($data['season_id']) &&
            isset($data['level']) && !empty($data['level']))
            return $this->getLevelRecords($data['season_id'], $data['level']);
        if (isset($data['season_id']) && !empty($data['season_id']))
            return $this->getSeasonRecords($data['season_id']);
        return null;
    }

    /**
     * Loads record by its id from database
     * @param int $id of the record to be loaded
     * @return array loaded records, null if not found
     */
    public function getRecordById(int $id) : ?array
    {
        $record = Db::queryOne('
            SELECT *
            FROM `records`
            WHERE `record_id` = ?
        ', array($id));
        if (!$record)
            return null;
        return $record;
    }

    /**
     * Loads every records from database
     * @return array loaded records
     */
    public function getRecords() : array
    {
        return Db::queryAll('
            SELECT *
            FROM `records`
        ');
    }

    /**
     * Loads all times from given level and season from database
     * @param int $id of the season
     * @param int $level where record have been set
     * @return array level times
     */
    public function getLevelRecords(int $season, int $level) : array
    {
        return Db::queryAll('
            SELECT *
            FROM `records`
            WHERE `record_season` = ? AND `record_level` = ?
            ORDER BY `record_time` ASC
        ', array($season, $level));
    }

    /**
     * Loads best time from each level in given season
     * @param int $id of the season
     * @return array best time of each level
     */
    public function getSeasonRecords(int $season) : array
    {
        $records = array();
        for ($i = 0; $i < 25; $i++)
        {
            $rec = Db::queryOne('
                SELECT *
                FROM `records`
                WHERE `record_season` = ? AND `record_level` = ?
                ORDER BY `record_time` ASC
                LIMIT 1
            ', array($season, $i + 1));
            if (empty($rec))
            {
                $records[$i] = $this->getNotSetRecord($season, $i + 1);
                continue;
            }
            $records[$i] = $rec;
        }
        return $records;
    }

    /**
     * Creates not set record on given season and level
     * @param int $season of record to be created
     * @param int $level of record to be created
     * @return array not set record on given season and level
     */
    public function getNotSetRecord(int $season, int $level) : array
    {
        return array(
            'record_id' => '',
            'record_holder' => '',
            'record_time' => '',
            'record_season' => $season,
            'record_level' => $level
        );
    }

    /**
     * Inserts given record to database
     * @param array $record to be inserted
     */
    public function addRecord(array $record) : void
    {
        Db::insert("records", $record);
    }

    /**
     * Updates given record
     * @param array $record to be updated
     */
    public function editRecord(array $record) : void
    {
        Db::update("records", $record,
                   "WHERE `record_id` = ?",
                   array($record['record_id']));
    }

    /**
     * Deletes record from database by given id
     * @param int $id of the record
     */
    public function removeRecord(array $data) : int
    {
        if (!isset($data['record_id']) || empty($data['record_id']))
            return 0;

        return Db::query('
            DELETE FROM `records`
            WHERE `record_id` = ?
        ', array($data['record_id']));
    }

    /**
     * Adds or edits record
     * @param array $data record data
     * @return array errors array
     */
    public function submitDialog(array $data) : array
    {
        $errors = array();
        $seasonManager = new SeasonManager();

        if (!isset($data['record_holder']) || empty($data['record_holder']))
            $errors['record_holder'] = 'Invalid';
        if (!isset($data['record_time']) || empty($data['record_time']))
            $errors['record_time'] = 'Invalid';
        if (!isset($data['record_season']) || empty($data['record_season']))
            $errors['record_season'] = 'Invalid';
        else if (!$seasonManager->getSeasonById($data['record_season']))
            $errors['record_season'] = 'Invalid';
        if (!isset($data['record_level']) || empty($data['record_level']))
            $errors['record_level'] = 'Invalid';
        else if ($data['record_level'] <= 0 || $data['record_level'] > 25)
            $errors['record_level'] = 'Invalid';
        
        if (!empty($errors))
            return $errors;

        $record = array(
            'record_id' => '',
            'record_holder' => $data['record_holder'],
            'record_time' => $data['record_time'],
            'record_season' => $data['record_season'],
            'record_level' => $data['record_level']
        );
        
        if (!isset($data['record_id']) || empty($data['record_id']))
        {
            $this->addRecord($record);
            return array();
        }
        
        $record['record_id'] = $data['record_id'];
        $this->editRecord($record);
        return array();
    }
}