<?php

/**
 * Created by PhpStorm.
 * User: Jim.Lenart
 * Date: 1/11/2016
 * Time: 10:34 AM
 */
class Team
{
    public $teamId;    //static identifier
    public $teamName;  //can change from season to season
    public $teamOwnerId; //can have multiple owners


    public function __construct($name, $id)
    {
        $this->teamName = $name;
        $this->teamId = $id;
    }

    public function LoadTeamInfoFromDB()
    {
        $matchCtr = 0;
        $teamData = array();

        $dbc = mysqli_connect('PC-DEV-229','jim.lenart','moonchild', 'angry_robots', '3306' )
        or die ('Error connecting to MySQL server.');

        $query = "SELECT * FROM angry_robots.teams ";

        $result = mysqli_query($dbc, $query)
        or die('Error querying database in LoadTeamInfoFromDB.');

        while ($row = mysqli_fetch_array($result)){

            $teamData[$matchCtr]['teamId'] = $row['team_id'];
            $teamData[$matchCtr]['teamName'] = $row['team_name'];

            $matchCtr++;
        }
        //var_dump($teamData);
        return $teamData;

    }
}
