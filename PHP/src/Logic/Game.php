<?php
/**
 * Created by PhpStorm.
 * User: edvin.bergstrom
 * Date: 2016-10-27
 * Time: 12:37
 *
 * för att redrera titta på state för varje time (sparar inga dupleter)
 *
 *Det som är var är vinn metod
 *
 * STATEID GREGEN ÄR HELT FEL I ALLA METODER TYP!
 *
 * FELET LIGGER NU I GENERERA TILES state id blir null ???????
 */

namespace Game;


use Game\Utils\Database;

class Game{

    private $characters;

    function __construct(){

        $this->characters = array("1"=>1,"2"=>1,"3"=>2,"4"=>3,"5"=>4,"6"=>4,"7"=>4,"8"=>5,"9"=>8,"10"=>1,"11"=>6,"12"=>1);

    }

    public static function isLand($x, $y, $gameid){
        $mysqli = Database::connect_db();

        $sql = "SELECT type FROM tiles WHERE id = (SELECT MAX(id) FROM tiles WHERE x_cord = ? and y_cord = ? AND gameid=?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("iii", $x,$y,$gameid);
        $stmt->execute();
        $stmt->bind_result($landtype);
        $stmt->fetch();
        if ($landtype==0){
            return true;
        }
        else return false;
        //return $landtype;
    }

    public static function isVisebel($gameid, $x, $y){

        $mysqli = Database::connect_db();

        $sql = "SELECT visebel FROM characters WHERE id = (SELECT characters FROM tiles WHERE id =(SELECT MAX(id) FROM tiles WHERE x_cord = ? and y_cord = ? AND gameid=?))";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("iii", $x,$y,$gameid);
        $stmt->execute();
        $stmt->bind_result($b);
        $stmt->fetch();
        //echo "b == ".$b.";";

        if (0===$b){
            //echo "b == fales<br>";
            return false;

        }
        else return true;



    }

    public static function getName($i)
    {
        switch ($i){
            case 1: return "Fältmarskalk";
            case 2: return "General";
            case 3: return "Överste";
            case 4: return "Major";
            case 5: return "Kapten";
            case 6: return "Löjtnant";
            case 7: return "Sergeant";
            case 8: return "Minröjare";
            case 9: return "Spejare";
            case 10: return "Spion";
            case 11: return "Mina";
            case 12: return "Flagga";
        }
        /*
                          1	Fältmarskalk	1
                          2	General	      1
                          3	Överste	      2
                          4	Major	        3
                          5	Kapten	      4
                          6	Löjtnant	    4
                          7	Sergeant	    4
                          8	Minör	        5
                          9	Spejare	      8
                          10 Spion	      1

                          11 Bomb         6
                          12 Flagga       1


                        */
    }

    public static function isEmpty($x, $y, $gameid)
    {
        $sql = "SELECT characters FROM tiles WHERE id = (SELECT MAX(id) FROM tiles WHERE x_cord = ? and y_cord = ? AND gameid=?)";
        $mysqli = Database::connect_db();
        $stmtTo = $mysqli->prepare($sql);
        $stmtTo->bind_param("iii", $x, $y, $gameid);
        $stmtTo->execute();
        $stmtTo->bind_result($c);
        $stmtTo->fetch();
        if (null==$c){
            return true;
        }
        else return false;

    }

    /**
     * @param $gameid
     * @return mixed
     *
     * max id
     */
    private static function getStateId($gameid){
        $mysqli = Database::connect_db();
        $sql= "SELECT max(id) FROM state WHERE gameid = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i",$gameid);
        $stmt->execute();
        $stmt->bind_result($StateID);
        $stmt->fetch();
        return $StateID;
    }

    /**
     * @param $gid
     * @return mixed
     *
     * Max nr
     */
    public static function getStateNr($gid){
        $mysqli = Database::connect_db();
        $sql = "SELECT max(stateNr) FROM state WHERE gameid=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i",$gid);
        $stmt->execute();
        $stmt->bind_result($sNr);

        $stmt->fetch();
        return$sNr;


    }

    public static function newCharacter($type, $id, $gid)
    {
        $mysqli = Database::connect_db();
        $sql = "INSERT INTO characters VALUES(0,?,?,?,FALSE)";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("iii", $type,$id,$gid);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public static function endFirstTurn($sqlTiles,$gameid,$uid)
    {
        $mysqli = Database::connect_db();
        $nextUser = Game::nextperson($gameid,$uid);
        $sid = Game::newState($gameid,$nextUser);
        for ($y = 0; $y < 10; $y++) {
            for ($x = 0; $x < 10; $x++) {
                if (isset($sqlTiles[$x][$y])){
                    //echo $x." <-x  y->  ".$y ."  cType->";
                    //echo $sqlTiles[$x][$y]."<br>";
                    $sql ="INSERT INTO tiles VALUES (0,0,?,?,?,?,?)";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param("iiiii",$x,$y,$gameid,$sid,$sqlTiles[$x][$y]);
                    $stmt->execute();
                }
            }
        }
    }


    /*Gamal kan tas bort
     * function turn1($type,$x,$y,$gameid){
        $mysqli = Database::connect_db();
        

        $sql = "SELECT type FROM tiles WHERE x_cord = ? and y_cord = ? AND stateid = 0 AND gameid=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("iiii", $toX,$toY,$gameid,$gameid);
        $stmt->execute();
        $stmt->bind_result($landtype);

        $stmt->fetch();

        if ($landtype==0) {
            if ($this->characters[$type] > 0) {

                $sql = "INSERT INTO characters VALUE (0,?,?,?,'false')";
                $stmtC = $mysqli->prepare($sql);
                $stmtC->bind_param("iii", $type, $uid, $gameid);
                $stmtC->execute();

                $cid = $stmtC->insert_id;

                $sql = "INSERT INTO tiles VALUE (0,0,?,?,?,0,?)";
                $stmtP = $mysqli->prepare($sql);
                $stmtP->bind_param("iiii", $x,$y,$gameid,$cid);
                $stmtP->execute();

            }else return;//caracternen finns inte
        }else return"felplasering";
        if ( empty($this->characters)){
            $sql = "SELECT user2,user1 FROM game WHERE id = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("i",$gameid);
            $stmt->execute();
            $stmt->bind_result($user2,$user1);
            $stmt->fetch();
            if ($user2==$uid){
                Game::newState($gameid,$user1);
            }
            else {
                Game::newState($gameid, $user2);
            }
            return;//till menyn/start sidan
        }
        else return;//till nästa plasering


}*/
    static function nextperson($gameid,$uid){
        $mysqli = Database::connect_db();
        $sql = "SELECT user2,user1 FROM game WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i",$gameid);
        $stmt->execute();
        $stmt->bind_result($user2,$user1);
        $stmt->fetch();
        if ($user2==$uid){
            return $user1;
        }
        else {
            return $user2;
        }
    }
    static function vem($gameid){
        $sql = "SELECT uid FROM state WHERE id = (SELECT max(id) FROM state WHERE gameid = ?)";
        $mysql = Database::connect_db();
        $stmt = $mysql->prepare($sql);
        $stmt->bind_param("i",$gameid);
        $stmt->execute();
        $stmt->bind_result($uid);
        if ($stmt->fetch()){
            return $uid;
        }

    }

    static function newGame($timePerTurn,$userid1,$user2)
    {
        $mysqli = Database::connect_db();
        $userid2 = Game::getId($user2);

        if ($userid2 != null or "") {

            $sql = "INSERT INTO game VALUE (0,5,?,?)";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("ii", $userid1, $userid2);
            $stmt->execute();

            $gameid = $stmt->insert_id;

            $stateid = Game::newState($gameid, $userid1, 1);

            $sql = "INSERT INTO tiles VALUE (0,?,?,?,?,?,NULL )";
            //id ,type INT, x_cord INT, y_cord INT, gameId INT, stateId INT, characters INT,
            $stmt = $mysqli->prepare($sql);

            for ($x = 0; $x < 10; $x++) {
                for ($y = 0; $y < 10; $y++) {
                    if (($x == 2 || $x == 3 || $x == 6 || $x == 7) && ($y == 4 || $y == 5)) {
                        $type = 1;
                    } else$type = 0;
                    $stmt->bind_param("iiiii", $type, $x, $y, $gameid, $stateid);
                    $stmt->execute();
                }
            }
            return true;

        }
        return false;
    }





    //alla batels vissar båda ckaracternerna + all historik (alla state)

    static function turn($fromX,$fromY,$toX,$toY,$gameid,$user){
        if (self::vem($gameid)==$user) {
            $mysqli = Database::connect_db();
            $sql = "SELECT characters FROM tiles WHERE id = (SELECT MAX(id) FROM tiles WHERE x_cord = ? and y_cord = ? AND gameid=?)";
            $stmtFrom = $mysqli->prepare($sql);
            $stmtFrom->bind_param("iii", $fromX, $fromY, $gameid);
            $stmtFrom->execute();
            $stmtFrom->bind_result($charactersFromSid);

            if ($stmtFrom->fetch()) {
                $mysqliTo = Database::connect_db();
                $sql = "SELECT type FROM characters WHERE id = ?";
                $stmtTo = $mysqliTo->prepare($sql);
                echo $charactersFromSid."<br>";
                $stmtTo->bind_param("i", $charactersFromSid);
                $stmtTo->execute();
                $stmtTo->bind_result($c);
                if ($stmtTo->fetch()) {
                    $charactersFrom = $c;
                }
            }

            $sql = "SELECT characters,type FROM tiles WHERE id = (SELECT MAX(id) FROM tiles WHERE x_cord = ? and y_cord = ? AND gameid=?)";
            $mysqli = Database::connect_db();
            $stmtTo = $mysqli->prepare($sql);
            $stmtTo->bind_param("iii", $toX, $toY, $gameid);
            $stmtTo->execute();
            $stmtTo->bind_result($charactersToid, $type);
            if ($stmtTo->fetch()) {
                if ($type == 0) {
                    $usernext = Game::nextperson($gameid,$user);
                    $stateid = self::newState($gameid,$usernext);
                    if ($charactersToid == null) {
                        echo "nästa ruta är tom";
                        self::move($fromX,$fromY,$toX,$toY,$gameid,$charactersToid,$charactersFromSid,true,$stateid);

                    } else {
                        $mysqli = Database::connect_db();
                        $sql = "SELECT type,uid FROM characters WHERE id = ?";
                        $stmt = $mysqli->prepare($sql);
                        $stmt->bind_param("i", $charactersToid);
                        $stmt->execute();
                        $stmt->bind_result($charactersTo, $userIdOnTo);
                        $stmt->fetch();
                        if ($userIdOnTo != $user) {
                            echo "To är ej min karavtar<br>";
                            echo $charactersFrom." from <br>";
                            echo $charactersTo." To <br>";
                            if ($charactersTo > 10) {
                                //om det är en bomb

                                switch ($charactersTo) {
                                    case 11: {
                                        echo "det är en bomb";
                                        if ($charactersFrom == 8) {
                                            echo "det är en minröjare";
                                            Game::move($fromX, $fromY, $toX, $toY, $gameid, $charactersToid, $charactersFrom, false,$stateid);
                                        } else {
                                            echo "det är vilkken som hälst utom minröjare";
                                            Game::removeTo($toX,$toY,$gameid,$charactersToid,$charactersFromSid,$stateid);
                                            Game::removeFrom($fromX,$fromY,$gameid,$charactersToid,$charactersFromSid,$stateid);
                                        }
                                    }break;
                                    //om flagga
                                    case 12: {

                                        echo "du vann";
                                        Game::victory($user,$gameid);
                                    }
                                }
                                //10 är spion 1 är Fältmarskalk
                            } else if (($charactersFrom == 10) && ($charactersTo == 1)) {

                                Game::move($fromX,$fromY,$toX,$toY,$gameid,$charactersToid,$charactersFromSid,false,$stateid);

                            } else if ($charactersFrom > $charactersTo) {//to är starkare
                                echo "remove from hgdfh<br>";
                                self::removeFrom($fromX,$fromY,$gameid,$charactersToid,$charactersFromSid,$stateid);

                            } else if ($charactersFrom == $charactersTo) {//bortmed båda
                                echo "remove to and from <br>";
                                self::removeTo($toX,$toY,$gameid,$charactersToid,$charactersFromSid,$stateid);
                                self::removeFrom($fromX,$fromY,$gameid,$charactersToid,$charactersFromSid,$stateid);
                            }
                            else {
                                echo "move zfasdf <br>";
                                Game::move($fromX,$fromY,$toX,$toY,$gameid,$charactersToid,$charactersFromSid,false,$stateid);
                            }
                        }else return;//Egenspelare i vägen
                    }
                } else return "vatten";
            }
        }else return;//fel person
    }


    //flyttar charactern fungerat vid "remove to" + "move"
    //är något fel
    static function move($fromX,$fromY,$toX,$toY,$gameid,$characterid,$charactersFromId,$tom,$stateid){
        echo "Move <br>";

        $mysqli = Database::connect_db();




        $sql= "INSERT INTO tiles VALUE (0,0,?,?,?,?,NULL )";
        //id,type,x,y,gameid,stateid, characters id

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("iiii", $fromX,$fromY,$gameid,$stateid);
        if ($stmt->execute()){
            echo "JA fråga 1<br>";
        }

        $sqla= "INSERT INTO tiles VALUE (0,0,?,?,?,?,?)";
        //id,type,x,y,gameid,stateid, characters id
        $mysqlii = Database::connect_db();
        $stmtl = $mysqlii->prepare($sqla);
        $stmtl->bind_param("iiiii", $toX,$toY,$gameid,$stateid,$charactersFromId);
        if ($stmtl->execute()){
            echo "ja FRÅGA 2 <br>".$charactersFromId;
        }

        if (!$tom){
            $sql = "UPDATE characters SET visebel = TRUE WHERE id = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("i", $characterid);
            $stmt->execute();


            $sql = "UPDATE characters SET visebel = TRUE WHERE id = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("i", $charactersFromId);
            $stmt->execute();
        }
    }

    static function removeFrom($fromX,$fromY,$gameid,$characterid,$charactersFromId,$stateid){
        $mysqli = Database::connect_db();
        $sql= "INSERT INTO tiles VALUE (0,0,?,?,?,?,NULL )";
        //id,type,x,y,gameid,stateid, characters id
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("iiii", $fromX,$fromY,$gameid,$stateid);
        $stmt->execute();

        $sql = "UPDATE characters SET visebel = TRUE WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $characterid);
        $stmt->execute();

        $sql = "UPDATE characters SET visebel = TRUE WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $charactersFromId);
        $stmt->execute();
    }
    static function removeTo($toX,$toY,$gameid,$charactersIdTo,$charactersFromId,$stateid){
        echo "remove to <br>";

        $mysqli = Database::connect_db();
        $sql = "INSERT INTO tiles VALUE (0,0,?,?,?,?,NULL )";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("iiii", $toX,$toY,$gameid,$stateid);
        $stmt->execute();

        $sql = "UPDATE characters SET visebel = TRUE WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $charactersIdTo);
        $stmt->execute();

        $sql = "UPDATE characters SET visebel = TRUE WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $charactersFromId);
        $stmt->execute();
    }

    /**
     * @param $gameid
     * @param $uid vem är next
     * @return int
     */
    static function newState($gameid, $uid){
        $mysqli = Database::connect_db();
        $statenr=Game::getStateNr($gameid)+1;
        $sql = "INSERT INTO state VALUE (?,0,?,?,?)";
        //TIMESTAMP , id, gameid, uid, STATRNR

        $date = date_create()->format("Y-m-d H:i:s");

        $stmt = $mysqli->prepare($sql);
        try {
            $stmt->bind_param("siii", $date,$gameid,$uid,$statenr);
            $stmt->execute();
            $stmt->fetch();
        }catch (Exception $e){
            $sql = "INSERT INTO state VALUE (?,0,?,?,1)";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("sii", $date,$gameid,$uid);
                $stmt->execute();
                $stmt->fetch();
        }
        $stateid=$stmt->insert_id;

        return $stateid;
    }
    static function findGame($uid){
        $mysqli = Database::connect_db();
        $sql="SELECT id FROM game WHERE user1 = ? OR user2 = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ii",$uid,$uid);
        $stmt->bind_result($gameid);
        $stmt->execute();
        $games = array();
        while ($stmt->fetch()) {
            $games[]=$gameid;
        }
        if (isset($gameid)) {
            return $games;
        }
        else return [];

    }

    static function getTime($gameid)
    {
        $mysqli = Database::connect_db();
        $sql = "SELECT timePerTurn FROM game WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $gameid);
        $stmt->bind_result($timePerTurn);
        $stmt->execute();
        $stmt->fetch();

        //echo $gameid;
        $mysqli = Database::connect_db();
        $sql = "SELECT timeStamp FROM state WHERE gameId = ? and id = (SELECT MAX(id) FROM state WHERE gameId = ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ii", $gameid, $gameid);
        $stmt->bind_result($lasttimestamp);
        $stmt->execute();
        $stmt->fetch();

        echo $lasttimestamp;
    }
    static function getCharacterTypeAndUserId($gameId,$x,$y){

        $mysqli = Database::connect_db();
        $sql = "SELECT characters FROM tiles WHERE id = (SELECT MAX(id) FROM tiles WHERE x_cord = ? and y_cord = ? AND gameid=?)";
        $stmtFrom = $mysqli->prepare($sql);
        $stmtFrom->bind_param("iii", $x, $y, $gameId);
        $stmtFrom->execute();
        $stmtFrom->bind_result($charactersId);

        if ($stmtFrom->fetch()) {
            $mysqli = Database::connect_db();
            $sql = "SELECT type , uid FROM characters WHERE id = ?";
            $stmtTo = $mysqli->prepare($sql);
            $stmtTo->bind_param("i", $charactersId);
            $stmtTo->execute();
            $stmtTo->bind_result($c,$uid);
            if ($stmtTo->fetch()) {
                $a = array('1'=>$c,'2'=>$uid);
                return $a;
            }
        }
        return $a = array("1"=>null,"2"=>null);

    }

    private static function victory($user, $gameid){

        $mysqli = Database::connect_db();
        $sql = "INSERT INTO state VALUE(?,0,?,?,(-1))";
        $stmt = $mysqli->prepare($sql);
        $date = date_create()->format("Y-m-d H:i:s");
        $stmt->bind_param("sii",$date,$gameid,$user);
        $stmt->execute();

    }

    public static function done($gameid){
        $mysqli = Database::connect_db();
        $sql = "SELECT uid FROM state WHERE stateNr = (-1) AND gameid = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i",$gameid);
        $stmt->execute();
        $stmt->bind_result($user);
        $stmt->fetch();
        if ($user != null or $user = "") {
            return $user;
        }
        else return -30;

    }

    private static function getId($user2)
    {
        $mysqli = Database::connect_db();
        $sql = "SELECT id FROM user WHERE uname=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s",$user2);
        $stmt->execute();
        $stmt->bind_result($user);
        $stmt->fetch();
        return $user;
    }


}