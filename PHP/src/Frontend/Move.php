<?php
/**
 * Created by PhpStorm.
 * User: edvin.bergstrom
 * Date: 2016-11-30
 * Time: 12:26
 */
session_start();
include "../Utils/User.php";
include "../Utils/Database.php";
include "../Logic/Game.php";
use Game\User;
use Game\Game;

if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];

    for ($i = 0; $i <= 100; $i++) {
        if (isset($_POST[$i])) {
            $xAndYFrom = $_POST[$i];
            break;
        }
    }
    $xFrom = substr($xAndYFrom, 0, 1);
    $yFrom = substr($xAndYFrom, 2, 1);




    $text = "<input type='hidden' value='".$xFrom."' name='xFrom'>";
    $text = $text. "<input type='hidden' value='".$yFrom."' name='yFrom'>";
    $antal = 0;
    for ($y = 0; $y < 10; $y++) {
        $text = $text . "<tr>";
        for ($x = 0; $x < 10; $x++) {
            $a = Game::getCharacterTypeAndUserId($_GET['gid'], $x, $y);
            $cType = $a['1'];
            //echo $a['1'];
            $ower = $a['2'];
            if (Game::isLand($x, $y, $_GET['gid'])) {
                if ($xFrom == $x && $yFrom == $y) {
                    $text = $text . "<td class='tile'><button class='selected' type='button' value='" . $x . " " . $y . "' name='" . $antal . "'>" . Game::getName($cType) . "</button></td>";
                } else if (($yFrom == $y && ($xFrom == $x + 1 || $xFrom == $x - 1)) || ($xFrom == $x && ($yFrom == $y + 1 || $yFrom == $y - 1))) {
                    if ($ower == $id) {
                        $text = $text . "<td class='tile'><button  type='button' class='ally' value='" . $x . " " . $y . "' name='" . $antal . "'>" . Game::getName($cType) . "</button></td>";
                    }
                    else{
                        if ($cType==null){
                            $text = $text . "<td class='tile'><button class='pMove' type='submit' value='" . $x . " " . $y . "' name='" . $antal . "'>Tom</button></td>";
                        }
                        elseif (Game::isVisebel($_GET['gid'],$x,$y)) {
                            $text = $text . "<td class='tile'><button class='pMove' type='submit' value='" . $x . " " . $y . "' name='" . $antal . "'>" . Game::getName($cType) . "</button></td>";
                        }else{
                            $text = $text . "<td class='tile'><button class='pMove' type='submit' value='" . $x . " " . $y . "' name='" . $antal . "'>???</button></td>";
                        }
                    }
                }else {
                    if ($ower==$id){
                        $text = $text . "<td class='tile'><button type='button' class='ally' value='" . $x . " " . $y . "' name='" . $antal . "'>" . Game::getName($cType) . "</button></td>";

                    }
                    elseif (Game::isEmpty($x,$y,$_GET['gid'])){
                        $text = $text . "<td class='tile'><button class='cantSelect' type='button' value='" . $x . " " . $y . "' name='" . $antal . "'>  Tom  </button></td>";

                    }
                    elseif (Game::isVisebel($_GET['gid'],$x,$y)){
                        $text = $text . "<td class='tile'><button class='enemy' type='button' value='" . $x . " " . $y . "' name='" . $antal . "'>" . Game::getName($cType) . "</button></td>";
                    }
                    else $text = $text . "<td class='tile'><button class='enemy' type='button' value='" . $x . " " . $y . "' name='" . $antal . "'>???</button></td>";
                }
            }
             else {
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
<header>Stratego <br><a href='playGame.php?gid=".$_GET['gid']."'>Deselect</a></header>


";echo "


<form action='sendToGame.php?gid=".$_GET['gid']."' method='post' >

<table class='gameBordp'>

".$text."

</table>
";echo "

"/*<table class='list'>
<tr>
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