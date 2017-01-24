<?php
/**
 * Created by PhpStorm.
 * User: edvin.bergstrom
 * Date: 2016-11-16
 * Time: 12:34
 */

session_start();
include "../Utils/User.php";
include "../Utils/Database.php";
include "../Logic/Game.php";
use Game\User;
use Game\Game;

if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    if (isset($_POST["Sname"])&& isset($_POST['timePerTurn'])){
        //echo "fsdjfnjksanfjknasjkfnajksnfjnsajknfjksandf <br>";
        Game::newGame($_POST['timePerTurn'],$id, $_POST["Sname"]);
    }




    echo "
    <!DOCTYPE html>
    <html>
    <head>
    
    
</head>
    <body>
    <form action='newGame.php' method='post'>
        <table>
        <tr><td>Mot vem (Screnname(id just nu))</td><td><input type='text' name='Sname'></td></tr>
        <tr><td>Tid per turn (timmar)</td><td><input type='number' name='timePerTurn' min='1' max='24'></td></tr>
        <tr><td colspan='2'><input type='submit' name='submit'></td> </tr>
    </table>
</form>
    
</body>
</html>
    
    
    ";


}