<?php

class SeasonManager
{
    /**
     * Loads season from database using given data
     * If data contains season_id, uses this to find season
     * If data contains season_year and season_name, uses this
     * @param array $data containing either season_id or both season_year and season_name
     * @return array loaded season, returns null if not found
     */
    public function getSeason(array $data) : ?array
    {
        if (isset($data['season_id']) && !empty($data['season_id']))
            return $this->getSeasonById($data['season_id']);
        if (isset($data['season_year']) && !empty($data['season_year']) &&
            isset($data['season_name']) && !empty($data['season_name']))
        {
            return $this->getSeasonByYearName(
                $data['season_year'],
                $data['season_name']
            );
        }
        return null;
    }

    /**
     * Loads season from database by its name and year
     * @param string $year of the season
     * @param string $name of the season
     * @return array loaded season, returns null if not found
     */
    public function getSeasonByYearName(string $year, string $name) : ?array
    {
        $season = Db::queryOne('
            SELECT *
            from `seasons`
            WHERE `season_year` = ? AND `season_name` = ?
        ', array($year, $name));
        if (!$season)
            return null;
        return $season;
    }

    /**
     * Loads season from database by its id
     * @param int $id of season to be loaded
     * @return array loaded season, returns null if not found
     */
    public function getSeasonById(int $id) : ?array
    {
        $season = Db::queryOne('
            SELECT *
            FROM `seasons`
            WHERE `season_id` = ?
        ', array($id));
        if (!$season)
            return null;
        return $season;
    }

    /**
     * Loads all seasons from database
     * @return array loaded seasons, returns null if no season found
     */
    public function getSeasons() : array
    {
        return Db::queryAll('
            SELECT *
            FROM `seasons`
            ORDER BY `season_year` DESC
        ');
    }

    /**
     * Inserts given season to database
     * @param array $season to be inserted
     */
    public function addSeason(array $season) : void
    {
        Db::insert("seasons", $season);
    }

    /**
     * Updates given season
     * @param array $season to be updated
     */
    public function editSeason(array $season) : void
    {
        Db::update("seasons", $season,
                   "WHERE `season_id` = ?",
                   array($season['season_id']));
    }

    /**
     * Deletes season from database by given id
     * @param array $params data array containing index season_id
     */
    public function removeSeason(array $data) : int
    {
        if (!isset($data['season_id']) || empty($data['season_id']))
            return 0;

        return Db::query('
            DELETE FROM `seasons`
            WHERE `season_id` = ?
        ', array($data['season_id']));
    }

    /**
     * Adds or edits season
     * @param array $season data
     * @return array errors array
     */
    public function submitDialog(array $data) : array
    {
        $errors = array();

        if (!isset($data['season_year']) || empty($data['season_year']))
            $errors['season_year'] = "Invalid";
        if (!isset($data['season_name']) || empty($data['season_name']))
            $errors['season_name'] = "Invalid";
        
        if (!empty($errors))
            return $errors;

        $season = array(
            'season_id' => '',
            'season_year' => $data['season_year'],
            'season_name' => $data['season_name']
        );
        
        if (isset($data['season_id']) && !empty($data['season_id']))
        {
            $season['season_id'] = $data['season_id'];
            $this->editSeason($season);
            return array();
        }

        $exists = $this->getSeason($data['season_year'], $data['season_name']);
        if ($exists)
        {
            $errors['exist'] = true;
            return $errors;
        }

        $this->addSeason($season);
        return array();
    }
}