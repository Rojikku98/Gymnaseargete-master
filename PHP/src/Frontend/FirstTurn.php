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

    if (!isset($_SESSION['notSetCharacters'])) {
        $_SESSION['notSetCharacters'] = array("1"=>1,"2"=>1,"3"=>2,"4"=>3,"5"=>4,"6"=>4,"7"=>4,"8"=>5,"9"=>8,"10"=>1,"11"=>6,"12"=>1);
    }
    if (!isset($_SESSION['usedxy'])) {
        $_SESSION['usedxy'] []= "";
    }
    if (!isset($_SESSION['sqlTiles'])){
        $_SESSION['sqlTiles'] = [];
    }


    $b=false;
    for ($i = 0; $i <= 100; $i++) {
        if (isset($_POST[$i])) {
            $xAndYFrom = $_POST[$i];
            $xFrom = substr($xAndYFrom, 0, 1);
            $yFrom = substr($xAndYFrom, 2, 1);
            $b = true;
            break;
        }
    }
    if ($b && isset($_POST['selectedCharacter'])) {


        $charactersid = Game::newCharacter($_POST['selectedCharacter'],$id,$_GET['gid']);
        if (Game::getStateNr($_GET['gid']) == 1){
            $statnr = 2;
        }
        else $statnr = 3;

        $_SESSION['sqlTiles'][$xFrom][$yFrom] = $charactersid;

        $_SESSION['usedxy'][]=$xFrom."".$yFrom;
        $_SESSION['cType'][$xFrom."".$yFrom]=$_POST['selectedCharacter'];



            //id,type,x,y,gameid,stateid,charatersid]
        $_SESSION['notSetCharacters'][$_POST['selectedCharacter']] --;
        //kollar om alla characterer är utsatta
        $b = true;
        for ($i=1;$i<13;$i++){
            if ($_SESSION['notSetCharacters'][$i]>0) {
                $b=false;
                break;
            }
        }


        if ($b){
            echo "hej";

            Game::endFirstTurn($_SESSION['sqlTiles'],$_GET['gid'],$id);
            unset($_SESSION['notSetCharacters']);
            unset($_SESSION['cType']);
            unset($_SESSION['usedxy']);
            unset($_SESSION['sqlTiles']);
            header("location:Main.php");


        }


    }



        $text = "";
        $antal = 0;
        for ($y = 0; $y < 10; $y++) {
            $text = $text . "<tr>";
            for ($x = 0; $x < 10; $x++) {
                //är ingentliges is water
                //echo $_GET['gid'];
                //echo Game::isLand($x, $y, $_GET['gid']);
                if (Game::isLand($x, $y, $_GET['gid'])) {
                    $a = Game::getCharacterTypeAndUserId($_GET['gid'], $x, $y);
                    $cType = $a['1'];
                    //echo $a['1'];
                    $ower = $a['2'];
                    if ($ower == $id || (in_array($x."".$y,$_SESSION['usedxy']))) {
                        if (isset($_SESSION['cType'][$x."".$y])){
                            $text = $text . "<td class='tile'><button class='used' type=\"button\" disabled value='" . $x . " " . $y . "' name='" . $antal . "' onclick='f(this.name)'>" . Game::getName($_SESSION['cType'][$x."".$y]) . "</button></td>";
                        }
                        else {
                            $text = $text . "<td class='tile'><button class='used' type='submit' type=\"button\" disabled value='" . $x . " " . $y . "' name='" . $antal . "' onclick='f(this.name)'>" . Game::getName($cType) . "</button></td>";
                        }
                        //om det är player ett eller två
                    } else if (Game::getStateNr($_GET['gid']) == 1) {
                        //player 1 har toppen
                        if ($y < 4) {
                            $text = $text . "<td class='tile'><button class='canSelect' type='submit' value='" . $x . " " . $y . "' name='" . $antal . "''>Tom</button></td>";
                        }
                        else {
                            $text = $text . "<td class='tile'><button class='cantSelect' type=\"button\" disabled value='" . $x . " " . $y . "' name='" . $antal . "'>cant select</button></td>";
                        }

                    }
                    else {
                        if ($y > 5) {
                            $text = $text . "<td class='tile'><button class='canSelect' type='submit' value='" . $x . " " . $y . "' name='" . $antal . "'>Tom</button></td>";
                        }
                        else {
                            $text = $text . "<td class='tile'><button class='cantSelect' type=\"button\" disabled value='" . $x . " " . $y . "' name='" . $antal . "'>cant select</button></td>";
                        }
                    }
                }
                 else {
                    //echo "vatten";
                    $text = $text . "<td class='tile'><input class='vatten' type='button'  value='vatten' name='vatten'></td>";
                }
                $antal++;
            }
            $text = $text . "</tr>";
        }
        $text2="";
    for ($i=1;$i<13;$i++){
        if ($_SESSION['notSetCharacters'][$i]>0)
        $text2=$text2."<input type='radio' name='selectedCharacter' value='".$i."'>".$_SESSION['notSetCharacters'][$i]." ".Game::getName($i) ;
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
<header class='Header'>Stratego <br><a href='main.php'>Back</a></header>

";echo "



<form action='FirstTurn.php?gid=".$_GET['gid']."' method='post' >
"/*<table class='b'>
<tr><td class='gameBord'>*/."
<div class='selectCharacters '>".$text2."</div>
<table class='gameBordp'>

".$text."

</table>
<div >
"/*<td  class='list'>.'"
<table class='info'>
<tr><td>info</td></tr><tr>
<td>Fältmarskalk</td><td colspan='2'>Vinner mot alla förutom Spion</td>
</tr>
<tr><td>General</td><td colspan='2'>Förlorar mot alla över</td></tr>
<tr><td>Överste</td><td colspan='2'>Förlorar mot alla över</td></tr>
<tr><td>Major</td><td colspan='2'>Förlorar mot alla över</td></tr>
<tr><td>Kapten</td><td colspan='2'>Förlorar mot alla över</td></tr>
<tr><td>Löjtnant</td><td colspan='2'>Förlorar mot alla över</td></tr>
<tr><td>Sergeant</td><td colspan='2'>Förlorar mot alla över</td></tr>
<tr><td>Minröjare</td><td colspan='2'>Förlorar mot alla över</td></tr>
<tr><td>Spejare</td><td colspan='2'>Förlorar mot alla över</td></tr>
<tr><td>Spion</td><td>Förlora mot alla förutom Fältmarskalk</td></tr>
<tr><td>Mina</td><td>Alla förlorar mor en minröjare förutom </td><td>Kan inte flyttas</td></tr>
<tr><td>Flagga</td><td>Vinner om man tar motstondarens flagga</td><td>Kan inte flyttas</td></tr>

</table>
*/."



</form>

</body>
    
    
    
</html>
    
    
    
    
    ";




}else header("location:index.php");