<?php
/**
 * Created by PhpStorm.
 * User: minecraft
 * Date: 2016-12-22
 * Time: 13:51
 */

use Game\Utils\Database;
include "../Utils/Database.php";


$sql1= "DELETE FROM tiles WHERE gameId = ?;";
$sql2="DELETE FROM characters WHERE gameId = ?;";
$sql3 = "DELETE FROM state WHERE gameId = ?;";
$sql4 = "DELETE FROM game WHERE id = ?";

$sql= $sql1.$sql2.$sql3.$sql3.$sql4;

$mysql = Database::connect_db();
$stmt = $mysql->prepare($sql1);
$stmt->bind_param("i",$_GET['gid']);
$stmt->execute();
$mysql = Database::connect_db();
$stmt = $mysql->prepare($sql2);
$stmt->bind_param("i",$_GET['gid']);
$stmt->execute();
$mysql = Database::connect_db();
$stmt = $mysql->prepare($sql3);
$stmt->bind_param("i",$_GET['gid']);
$stmt->execute();
$mysql = Database::connect_db();
$stmt = $mysql->prepare($sql4);
$stmt->bind_param("i",$_GET['gid']);
$stmt->execute();

header("location:main.php");