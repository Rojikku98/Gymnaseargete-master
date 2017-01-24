<?php
/**
 * Created by PhpStorm.
 * User: edvin.bergstrom
 * Date: 2016-10-25
 * Time: 14:40
 */

namespace Game\Utils;


class Database{


    /**
     * @param $name
     */
    static function selectAll($sql){

        $mysqli = Database::connect_db();


        $stmt = $mysqli->prepare($sql);

        return $stmt;

    }
    static function hasha($pw){
        return password_hash($pw,PASSWORD_DEFAULT);
    }

    static function connect_db() {

        $mysqli = mysqli_connect('localhost', 'root', '', 'stratego');

        if (!$mysqli->set_charset("utf8")) {
            echo "Fel vid inställning av teckentabell utf8: %s\n". $mysqli->error;
        } else {
            //echo "hfsuhdufshudhfu";

        }
        if ($mysqli->connect_errno) {
            echo "Misslyckades att ansluta till MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }

        return $mysqli;
    }




}