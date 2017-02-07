<?php
/**
 * Created by PhpStorm.
 * User: edvin.bergstrom
 * Date: 2016-11-11
 * Time: 11:55
 */
session_start();
include "../Utils/User.php";
include "../Utils/Database.php";
include "../Logic/Game.php";
use Game\User;
use Game\Game;

if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    if (Game::getStateNr($_GET['gid'])<=2){
        header("location:FirstTurn.php?gid=" . $_GET['gid']);
    }else {


        $text = "";
        $antal = 0;
        for ($y = 0; $y < 10; $y++) {
            $text = $text . "<tr>";
            for ($x = 0; $x < 10; $x++) {

                //är ingentliges is water
                //echo $_GET['gid'];
                if (Game::isLand($x, $y, $_GET['gid'])) {

                    $a = Game::getCharacterTypeAndUserId($_GET['gid'], $x, $y);
                    $cType = $a['1'];
                    //echo $a['1'];
                    $ower = $a['2'];
                    if ($ower == $id) {
                        if ($cType <= 10) {
                            $text = $text . "<td class='tile'><button class='ally' type='submit' value='" . $x . " " . $y . "' name='" . $antal . "' onclick='f(this.name)'>" . Game::getName($cType) . "</button></td>";
                        }else{
                            $text = $text . "<td class='tile'><button class='ally' type='button' value='" . $x . " " . $y . "' name='" . $antal . "' onclick='f(this.name)'>" . Game::getName($cType) . "</button></td>";
                        }
                    }
                    elseif (Game::isEmpty($x,$y,$_GET['gid'])){
                        $text = $text . "<td class='tile'><button class='cantSelect' value='" . $x . " " . $y . "' name='" . $antal . "' >  Tom  </button></td>";

                    }
                    else {
                        if (Game::isVisebel($_GET['gid'],$x,$y)) {
                            $text = $text . "<td class='tile'><button class='enemy' value='" . $x . " " . $y . "' name='" . $antal . "' >" . Game::getName($cType) . "</button></td>";
                        }else{
                            $text = $text . "<td class='tile'><button class='enemy' value='" . $x . " " . $y . "' name='" . $antal . "' >???</button></td>";
                        }
                    }
                } else {
                    //echo "vatten";
                    $text = $text . "<td class='tile'><input class='vatten' type='button' value='vatten' name='vatten'></td>";
                }
                $antal++;
            }
            $text = $text . "</tr>";
        }


        echo "
    <!DOCTYPE html>
    <html>
    <head>
    
    <title>play.php</title>
        <link rel='stylesheet' type='text/css' href='style.css'>
        <meta charset='utf-8'>
               
    
</head>
<body class='index'>
<header>Stratego<br><nav><a href='main.php'>Back</a></nav></header>

";
        echo "



<form action='Move.php?gid=" . $_GET['gid'] . "' method='post' >

<table class='gameBordp'>

" . $text . "

</table>

</form>

</body>
    
    
    
</html>
    
    
    
    
    ";


    }

}else header("location:index.php");