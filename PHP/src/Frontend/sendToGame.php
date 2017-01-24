<?php
/**
 * Created by PhpStorm.
 * User: edvin.bergstrom
 * Date: 2016-11-30
 * Time: 12:54
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
            $xAndYTo = $_POST[$i];
            $xTo = substr($xAndYTo, 0, 1);
            $yTo = substr($xAndYTo, 2, 1);
            break;
        }
    }
    $fromX = $_POST['xFrom'];
    $fromY = $_POST['yFrom'];

    Game::turn($fromX,$fromY,$xTo,$yTo,$_GET['gid'],$id);

    header("location:main.php");



}else header("location:index.php");