<?php
namespace Game;


use Game\Utils\Database;

class User{

    private $name;
    private $id;
    private $uname;
    private $epost;

    function __construct($uname1,$password){

        $mysqli = Database::connect_db();

        $sql = "SELECT id, uname, name, epost FROM user WHERE uname = ? AND password = ?";

        $password = Database::hasha($password);
        $pas=null;
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ss", $uname,$password);
        $stmt->execute();
        $stmt->bind_result($id,$uname,$name,$epost);
        if ($stmt->fetch()) {
            $this->id=$id;
            $this->uname=$uname;
            $this->epost=$epost;
            $this->name=$name;
        }
    }

    static function checkLogin($uname,$password){
        $mysqli = Database::connect_db();

        $sql = "SELECT password FROM user WHERE uname = ?";

        //$password = Database::hasha($password);

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $uname);
        $stmt->execute();
        $stmt->bind_result($passwordU);
        if ($stmt->fetch()) {
            if (password_verify($password, $passwordU)) {
                return true;
            } else {
                return false;
            }
        }
    }

    public static function newUser($uname,$name,$password,$epost){
        $mysqli = Database::connect_db();

        $sql = "SELECT id FROM user WHERE uname = ? ";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s",$uname);
        $stmt->bind_result($id);
        $stmt->execute();

        if (!$stmt->fetch()) {

            $sql = "INSERT INTO user VALUE (0,?,?,?,?)";

            //echo "<br><br> pass : ".$password;
            //echo "<br><br> epost : ".$epost;

            $password = Database::hasha($password);

            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("ssss", $uname, $name, $password, $epost);
            if ($stmt->execute()) {
                return true;
            }
        }
        else return false;
    }
    public static function getId($umane,$password){
        $mysqli = Database::connect_db();

        $sql = "SELECT id FROM user WHERE uname = ? ";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s",$umane);
        $stmt->bind_result($id);
        $stmt->execute();
        $stmt->fetch();
        return $id;
    }
    public static function getName($id){
        $mysqli = Database::connect_db();

        $sql = "SELECT name FROM user WHERE id = ? ";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i",$id);
        $stmt->bind_result($name);
        $stmt->execute();
        $stmt->fetch();
        return $name;
    }
    public static function getUname($id){
        $mysqli = Database::connect_db();

        $sql = "SELECT uname FROM user WHERE id = ? ";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i",$id);
        $stmt->bind_result($Uname);
        $stmt->execute();
        $stmt->fetch();
        return $Uname;
    }
    public static function getEpost($id){
        $mysqli = Database::connect_db();

        $sql = "SELECT epost FROM user WHERE id = ? ";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i",$id);
        $stmt->bind_result($epost);
        $stmt->execute();
        $stmt->fetch();
        return $epost;
    }






}