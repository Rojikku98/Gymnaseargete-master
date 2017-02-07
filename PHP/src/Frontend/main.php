<?php
/**
 * Created by PhpStorm.
 * User: edvin.bergstrom
 * Date: 2016-11-11
 * Time: 11:55
 *
 * lägg till så man kan se speplane även när det inte är sin egen tur genom att en ny sida som play game fast utan en form (Borde fungera)
 *
 * Nytt spel ska bara gå att lägga till spelare som finns (ett if stamet som kollar od fech id fungerar på user name)
 * reuneran null eller en sifra
 */
session_start();
include "../Utils/User.php";
include "../Utils/Database.php";
include "../Logic/Game.php";
use Game\User;
use Game\Game;

if (isset($_SESSION['notSetCharacters'])) {

    unset($_SESSION['notSetCharacters']);

}
if (isset($_SESSION['usedxy'])) {

    unset($_SESSION['usedxy']);
}
if (isset($_SESSION['sqlTiles'])){
    unset($_SESSION['sqlTiles']);
}

if (isset($_SESSION['id'])){
    $id = $_SESSION['id'];

    $gameid = Game::findGame($id);
    $text="";
    foreach ($gameid as $gid) {
       if (Game::done($gid)==$id) {
           $text = $text . "<tr class='gameListTr'><td>" . $gid . "</td><td>Du vann det här spelet</td><td><a href='delete.php?gid=" . $gid . "'>Delete</a></td><td>";
       }elseif (Game::done($gid) == Game::nextperson($gameid,$id)) {
           $text = $text . "<tr class='gameListTr'><td>" . $gid . "</td><td>Du förlorade det här spelet det ära spelet</td><td><a href='Delete.php?gid=" . $gid . "'>Delete</a></td></tr>";
       }
        if ($_SESSION['id'] == Game::vem($gid)) {
            $text = $text . "<tr class='gameListTr'><td>" . $gid . "</td><td><a href='playGame.php?gid=" . $gid . "'>play</a></td><td><a href='Delete.php?gid=".$gid."'>Delete</a></td></tr>";
        }
        else {
            $text = $text . "<tr class='gameListTr'><td>" . $gid . "</td><td> Det är ej din tur <a href='showGameBord.php?gid=" . $gid . "'><br>Se spelplanen</a> </td><td><a href='deleteGame.php'>Delete</a></td></tr>";
        }
    }
    if (isset($_POST["Sname"])){

        if (Game::newGame(5,$id, htmlentities($_POST["Sname"]))) {
            header("location:main.php");
        }else{
            echo "Kunde inte skapa ett nytt spel kontrilera andvändar namet";
        }
    }


    echo "<!DOCTYPE html>
    
<html>
    <head>
        <title>Main.php</title>
        <link rel='stylesheet' type='text/css' href='style.css'>

        <meta charset='utf-8'>
    </head>
    <body class='index'>
        <header >Stratego <br><nav>
        <a href='logout.php'>Logout</a></nav> </header>
        
    <div name='d' class ='newGame'>
        <form action='main.php' method='post'>
            <table class='newGameT'>
                <tr><td class='t' colspan='2' align='center'>New Game</td></tr>
                <tr><td class='l'>Mot vem</td><td class='r'><input type='text' name='Sname'></td></tr>
                <tr><td colspan='2'><input type='submit' name='submit'></td> </tr>
            </table>
        </form>
    </div>
        
        <table class='gameList'>
        
        ".$text."
        </table>
    </body>
        
</html>
    ";



}else header("location:index.php");